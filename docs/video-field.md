# Video Field

## The Field

The Video field type lets you add videos to your entries. Retrieving video information is then pretty easy : a video variable is provided which lets you retrieve all the information related to your video.

## Output

The Video field returns a [Video model](video-model.md) which you can use to access a video’s attributes from your templates.

```twig
{% set video = entry.video %}

{% if video %}
    {% if not video.hasErrors('url') %}
        <ul>
            <li>title: {{ video.title }}</li>
            <li>url: {{ video.url }}</li>
            <li>embed: {{ video.embed({ width: 300, height: 200 }) }}</li>
        </ul>
    {% else %}
        <p>Video has errors:</p>
        <ul>
            {% for error in video.getErrors('url') %}
                <li>{{ error }}</li>
            {% endfor %}
        </ul>
    {% endif %}
{% else %}
    <p>No video.</p>
{% endinf %}
```