![Logo](https://github.com/markocupic/markocupic/blob/main/logo.png)

# Contao Bootstrap Responsive Youtube Embed
This bundle provides a [Youtube/Vimeo/Dropbox Player](https://getbootstrap.com/docs/5.2/helpers/ratio/#example) content element for the [Contao CMS](https://contao.org/).
Create responsive video embeds based on the width of the parent by creating an intrinsic ratio that scales on any device.

## Requirements

- Contao 5.3 or later, including Contao 6
- Bootstrap 5 (the player uses the `ratio` helper classes)

![Frontend](docs/images/frontend.png)

## Video Id
In the backend you have to fill in the video id input.

| Youtube                                                                     | Vimeo                                                       | Dropbox                                                                                           |
|-----------------------------------------------------------------------------|-------------------------------------------------------------|---------------------------------------------------------------------------------------------------|
| https://www.youtube.com/watch?v=###movieId###                               | https://vimeo.com/###movieId###                             | https://dl.dropboxusercontent.com/s/###movieId###                                                 |
| Get the shareable youtube link: https://www.youtube.com/watch?v=a7D3A_wwl0g | Get the shareable vimeo link: https://vimeo.com/479750109   | Get the shareabe dropbox link: https://dl.dropboxusercontent.com/s/wtx6x44y61wsxj/sample.mp4?dl=0 |
| Everything from "?v=" belongs to the movieId => a7D3A_wwl0g                | Everything from ".com/" belongs to the movieId => 479750109 | Everything from ".com/s/" belongs to the movieId => wtx6x44y61wsxj/sample.mp4?dl=0                |

## Contao Inserttag

The extension supports Contao Insert tags to embed videos.

```
<div>
    {{bootstrapResponsiveYoutubeEmbed::a7D3A_wwl0g}}
    <!-- or a bit more complex -->
    {{bootstrapResponsiveYoutubeEmbed::a7D3A_wwl0g?autoplay=1&caption=Lorem ipsum&playerAspectRatio=4x3}}
</div>
```

Options: `autoplay` (`1` or `true`), `caption`, `playerAspectRatio` (`1x1`, `4x3`, `16x9`, `21x9`) and `playerType` (`youtube`, `vimeo`, `dropbox`). Without `playerType`, numeric ids are treated as Vimeo videos and all other ids as YouTube videos.

## Templates

- `content_element/bootstrap_youtube_responsive_embed.html.twig`: the content element
- `component/_bootstrap_youtube_responsive_embed.html.twig`: the player itself, used by the content element and the insert tag

Both templates use the variables `player_type`, `movie_id`, `aspect_ratio`, `autoplay` and `caption`. The component provides the blocks `player` and `caption`.

### Upgrading to version 3

The template variables have been renamed (e.g. `playerAspectRatio` is now `aspect_ratio`, `movieId` is now `movie_id`) and the player has been moved to the component template. Custom templates of the content element have to be adjusted.
