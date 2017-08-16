<?php
/**
 * @link      https://dukt.net/craft/videos/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/videos/docs/license
 */

namespace dukt\videos\gateways;

use dukt\videos\base\Gateway;
use dukt\videos\errors\VideoNotFoundException;
use dukt\videos\models\Collection;
use dukt\videos\models\Section;
use dukt\videos\models\Video;
use GuzzleHttp\Client;

/**
 * YouTube represents the YouTube gateway
 *
 * @author    Dukt <support@dukt.net>
 * @since     1.0
 */
class YouTube extends Gateway
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function getIconAlias(): string
    {
        return '@dukt/videos/icons/youtube.svg';
    }

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function getName(): string
    {
        return "YouTube";
    }

    /**
     * Returns the OAuth provider’s name.
     *
     * @return string
     */
    public function getOauthProviderName()
    {
        return 'Google';
    }

    /**
     * Returns the OAuth provider’s API console URL.
     *
     * @return string
     */
    public function getOauthProviderApiConsoleUrl(): string
    {
        return 'https://console.developers.google.com/';
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function getOauthScope()
    {
        return [
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/youtube',
            'https://www.googleapis.com/auth/youtube.readonly'
        ];
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function getOauthAuthorizationOptions()
    {
        return [
            'access_type' => 'offline',
            'approval_prompt' => 'force'
        ];
    }

    /**
     * @inheritDoc
     *
     * @param array $options
     *
     * @return \Dukt\OAuth2\Client\Provider\Google
     */
    public function createOauthProvider(array $options)
    {
        return new \Dukt\OAuth2\Client\Provider\Google($options);
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function getExplorerSections(): array
    {
        $sections = [];


        // Library

        $sections[] = new Section([
            'name' => "Library",
            'collections' => [
                new Collection([
                    'name' => "Uploads",
                    'method' => 'uploads',
                ])
            ]
        ]);


        // Playlists

        $playlists = $this->getCollectionsPlaylists();

        if (is_array($playlists)) {
            $collections = [];

            foreach ($playlists as $playlist) {
                $collections[] = new Collection([
                    'name' => $playlist['title'],
                    'method' => 'playlist',
                    'options' => ['id' => $playlist['id']],
                ]);
            }

            if (count($collections) > 0) {
                $sections[] = new Section([
                    'name' => "Playlists",
                    'collections' => $collections,
                ]);
            }
        }

        return $sections;
    }

    /**
     * @inheritDoc
     *
     * @param string $id
     *
     * @return Video
     * @throws VideoNotFoundException
     */
    public function getVideoById(string $id)
    {
        $data = $this->get('videos', [
            'query' => [
                'part' => 'snippet,statistics,contentDetails',
                'id' => $id
            ]
        ]);

        $videos = $this->parseVideos($data['items']);

        if (count($videos) == 1) {
            return array_pop($videos);
        }

        throw new VideoNotFoundException('Video not found.');
    }

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function getEmbedFormat(): string
    {
        return "https://www.youtube.com/embed/%s?wmode=transparent";
    }

    /**
     * @inheritDoc
     *
     * @param $url
     *
     * @return bool|string
     */
    public function extractVideoIdFromUrl(string $url)
    {
        // check if url works with this service and extract video_id

        $video_id = false;

        $regexp = ['/^https?:\/\/(www\.youtube\.com|youtube\.com|youtu\.be).*\/(watch\?v=)?(.*)/', 3];

        if (preg_match($regexp[0], $url, $matches, PREG_OFFSET_CAPTURE) > 0) {
            // regexp match key
            $match_key = $regexp[1];

            // define video id
            $video_id = $matches[$match_key][0];

            // Fixes the youtube &feature_gdata bug
            if (strpos($video_id, "&")) {
                $video_id = substr($video_id, 0, strpos($video_id, "&"));
            }
        }

        // here we should have a valid video_id or false if service not matching
        return $video_id;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Returns an authenticated Guzzle client
     *
     * @return Client
     */
    protected function createClient()
    {
        $options = [
            'base_uri' => $this->getApiUrl(),
            'headers' => [
                'Authorization' => 'Bearer '.$this->token->getToken()
            ]
            /*'query' => [
                'access_token' => $this->token->accessToken
            ],*/
        ];

        return new Client($options);
    }

    /**
     * Returns a list of favorite videos
     *
     * @param array $params
     *
     * @return array
     */
    protected function getVideosFavorites($params = [])
    {
        return $this->performVideosRequest('favorites', $params);
    }

    /**
     * Returns a list of videos in a playlist
     *
     * @param array $params
     *
     * @return array
     */
    protected function getVideosPlaylist($params = [])
    {
        $pagination = $this->pagination($params);

        $query = [
            'part' => 'id,snippet',
            'playlistId' => $params['id'],
            'maxResults' => $pagination['perPage']
        ];

        if (!empty($pagination['moreToken'])) {
            $query['pageToken'] = $pagination['moreToken'];
        }

        $playlistItemsData = $this->get('playlistItems', ['query' => $query]);

        $videoIds = [];

        foreach ($playlistItemsData['items'] as $item) {
            $videoId = $item['snippet']['resourceId']['videoId'];

            array_push($videoIds, $videoId);
        }

        $videoIds = implode(",", $videoIds);

        $videosData = $this->get('videos', [
            'query' => [
                'part' => 'snippet,statistics,contentDetails',
                'id' => $videoIds
            ]
        ]);

        $videos = $this->parseVideos($videosData['items']);

        $more = false;

        if (!empty($playlistItemsData['nextPageToken']) && count($videos) > 0) {
            $more = true;
        }

        return [
            'prevPage' => (isset($playlistItemsData['prevPageToken']) ? $playlistItemsData['prevPageToken'] : null),
            'moreToken' => (isset($playlistItemsData['nextPageToken']) ? $playlistItemsData['nextPageToken'] : null),
            'videos' => $videos,
            'more' => $more
        ];
    }

    /**
     * Returns a list of videos from a search request
     *
     * @param array $params
     *
     * @return array
     */
    protected function getVideosSearch($params = [])
    {
        $pagination = $this->pagination($params);

        $query = [
            'part' => 'id',
            'type' => 'video',
            'q' => $params['q'],
            'maxResults' => $pagination['perPage']
        ];

        if (!empty($pagination['moreToken'])) {
            $query['pageToken'] = $pagination['moreToken'];
        }

        $data = $this->get('search', ['query' => $query]);

        foreach ($data['items'] as $item) {
            $videoIds[] = $item['id']['videoId'];
        }

        if (!empty($videoIds)) {
            $videoIds = implode(",", $videoIds);

            $videosData = $this->get('videos', [
                'query' => [
                    'part' => 'snippet,statistics,contentDetails',
                    'id' => $videoIds
                ]
            ]);

            $videos = $this->parseVideos($videosData['items']);

            $more = false;

            if (!empty($data['nextPageToken']) && count($videos) > 0) {
                $more = true;
            }

            return [
                'prevPage' => (isset($data['prevPageToken']) ? $data['prevPageToken'] : null),
                'moreToken' => (isset($data['nextPageToken']) ? $data['nextPageToken'] : null),
                'videos' => $videos,
                'more' => $more
            ];
        }

        return [];
    }

    /**
     * Returns a list of uploaded videos
     *
     * @param array $params
     *
     * @return array
     */
    protected function getVideosUploads($params = [])
    {
        return $this->performVideosRequest('uploads', $params);
    }

    // Private Methods
    // =========================================================================

    /**
     * @return string
     */
    private function getApiUrl()
    {
        return 'https://www.googleapis.com/youtube/v3/';
    }

    /**
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    private function getCollectionsPlaylists($params = [])
    {
        $data = $this->get('playlists', [
            'query' => [
                'part' => 'snippet',
                'mine' => 'true'
            ]
        ]);

        return $this->parseCollections($data['items']);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    private function pagination($params = [])
    {
        $pagination = [
            'page' => 1,
            'perPage' => $this->getVideosPerPage(),
            'moreToken' => false
        ];

        if (!empty($params['perPage'])) {
            $pagination['perPage'] = $params['perPage'];
        }

        if (!empty($params['moreToken'])) {
            $pagination['moreToken'] = $params['moreToken'];
        }

        return $pagination;
    }

    /**
     * @param $item
     *
     * @return array
     */
    private function parseCollection($item)
    {
        $collection = [];
        $collection['id'] = $item['id'];
        $collection['title'] = $item['snippet']['title'];
        $collection['totalVideos'] = 0;
        $collection['url'] = 'title';

        return $collection;
    }

    /**
     * @param $items
     *
     * @return array
     */
    private function parseCollections($items)
    {
        $collections = [];

        foreach ($items as $item) {
            $collection = $this->parseCollection($item);

            array_push($collections, $collection);
        }

        return $collections;
    }

    /**
     * @param $data
     *
     * @return Video
     */
    private function parseVideo($data)
    {
        $video = new Video;
        $video->raw = $data;
        $video->authorName = $data['snippet']['channelTitle'];
        $video->authorUrl = "http://youtube.com/channel/".$data['snippet']['channelId'];
        $video->date = strtotime($data['snippet']['publishedAt']);
        $video->description = $data['snippet']['description'];
        $video->gatewayHandle = 'youtube';
        $video->gatewayName = 'YouTube';
        $video->id = $data['id'];
        $video->plays = $data['statistics']['viewCount'];
        $video->title = $data['snippet']['title'];
        $video->url = 'http://youtu.be/'.$video->id;


        // Video Duration

        $interval = new \DateInterval($data['contentDetails']['duration']);
        $video->durationSeconds = ($interval->d * 86400) + ($interval->h * 3600) + ($interval->i * 60) + $interval->s;


        // Thumbnails

        $largestSize = 0;

        if (is_array($data['snippet']['thumbnails'])) {
            foreach ($data['snippet']['thumbnails'] as $thumbnail) {
                if ($thumbnail['width'] > $largestSize) {
                    // Set thumbnail source with the largest thumbnail
                    $video->thumbnailSource = $thumbnail['url'];
                    $largestSize = $thumbnail['width'];
                }
            }
        }


        // Privacy

        if (isset($data['status']['privacyStatus'])) {
            switch ($data['status']['privacyStatus']) {
                case 'private':
                    $video->private = true;
                    break;
            }
        }

        return $video;
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function parseVideos($data)
    {
        $videos = [];

        foreach ($data as $videoData) {
            $video = $this->parseVideo($videoData);

            array_push($videos, $video);
        }

        return $videos;
    }

    /**
     * @param       $playlist
     * @param array $params
     *
     * @return array
     */
    private function performVideosRequest($playlist, $params = [])
    {
        $pagination = $this->pagination($params);

        $channelsResponse = $this->get('channels', [
            'query' => [
                'part' => 'contentDetails',
                'mine' => 'true'
            ]
        ]);

        foreach ($channelsResponse['items'] as $channel) {
            $uploadsListId = $channel['contentDetails']['relatedPlaylists'][$playlist];

            $query = [
                'part' => 'id,snippet',
                'playlistId' => $uploadsListId,
                'maxResults' => $pagination['perPage']
            ];

            if (!empty($pagination['moreToken'])) {
                $query['pageToken'] = $pagination['moreToken'];
            }

            $playlistItemsData = $this->get('playlistItems', ['query' => $query]);

            $videoIds = [];

            foreach ($playlistItemsData['items'] as $item) {
                $videoId = $item['snippet']['resourceId']['videoId'];

                array_push($videoIds, $videoId);
            }

            $videoIds = implode(",", $videoIds);

            $videosData = $this->get('videos', [
                'query' => [
                    'part' => 'snippet,statistics,contentDetails,status',
                    'id' => $videoIds
                ]
            ]);

            $videos = $this->parseVideos($videosData['items']);

            $more = false;

            if (!empty($playlistItemsData['nextPageToken']) && count($videos) > 0) {
                $more = true;
            }

            return [
                'prevPage' => (isset($playlistItemsData['prevPageToken']) ? $playlistItemsData['prevPageToken'] : null),
                'moreToken' => (isset($playlistItemsData['nextPageToken']) ? $playlistItemsData['nextPageToken'] : null),
                'videos' => $videos,
                'more' => $more
            ];
        }
    }
}
