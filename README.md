# Polyglot_XH

Polyglot_XH offers advanced features for multilingual CMSimple_XH websites.
The main point is that it allows to mark individual pages of different languages
as actual translations, so visitors as well as the admin can quickly switch
between the translations of a certain page.

Furthermore the plugin automatically emits information mainly for search
engines regarding available translations of the current page (so called
[hreflang links](https://support.google.com/webmasters/answer/189077?hl=en)).

- [Requirements](#requirements)
- [Download](#download)
- [Installation](#installation)
- [Settings](#settings)
- [Usage](#usage)
- [Troubleshooting](#troubleshooting)
- [License](#license)
- [Credits](#credits)

## Requirements

Polyglot_XH is a plugin for CMSimple_XH.
It requires CMSimple_XH ≥ 1.7.0 with the [Plib_XH plugin](https://github.com/cmb69/plib_xh)
and PHP ≥ 7.1.0.

## Download

The [lastest release](https://github.com/cmb69/polyglot_xh/releases/latest)
is available for download on Github.

## Installation

The installation is done as with many other CMSimple_XH plugins.

1. Backup the data on your server.
1. Unzip the distribution on your computer.
1. Upload the whole directory `polyglot/` to your server into
   the `plugins/` directory of CMSimple_XH.
1. Give write permissions to the subdirectories `cache/`, `config/`, `css/` and
   `languages/`.
1. Go to `Plugins` → `Polyglot` in the back-end
   to check if all requirements are fulfilled.

## Settings

The configuration of the plugin  is done as with many other CMSimple_XH plugins
in the back-end of the website. Go to `Plugins` → `Polyglot`.

You can change the default settings of Polyglot_XH under `Config`. Hints for
the options will be displayed when hovering over the help icon with your
mouse.

Localization is done under `Language`. You can translate the character
strings to your own language (if there is no appropriate language file
available), or customize them according to your needs.

The look of Polyglot_XH can be customized under `Stylesheet`.

## Usage

To use the advanced language menu of Polyglot_XH replace the call to
`languagemenu()` in your template(s) with:

    <?php echo polyglot_languagemenu()?>

To make this actually work, you have to tag individual pages of different
languages appropriately. You can do this in the tab `Polyglot` above the
content editor; simply set the same unique tag for the original page and for its
translations.

In the plugin administration (`Plugins` → `Polyglot` → `Translations`)
you can get an overview of which pages are already translated. The page headings
as well as the translations are linked to the respective pages in edit mode, so
you can quickly repair possibly missing Polyglot_XH tags.

Note that the relations between pages of different languages are cached in
`plugins/polyglot/cache/translations.dat` for performance reasons.
You can delete that file at any time; the cache will automatically be recreated
on the next page request.

## Troubleshooting

Report bugs and ask for support either on
[Github](https://github.com/cmb69/polyglot_xh/issues)
or in the [CMSimple_XH Forum](https://cmsimpleforum.com/).

## License

Polyglot_XH is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Polyglot_XH is distributed in the hope that it will be useful,
but *without any warranty*; without even the implied warranty of
*merchantibility* or *fitness for a particular purpose*. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Polyglot_XH.  If not, see <https://www.gnu.org/licenses/>.

Copyright 2012-2023 Christoph M. Becker

## Credits

Polyglot_XH was inspired by
[Multilang_XH](http://cmsimplewiki-com.keil-portal.de/doku.php?id=plugins:multilang_xh)
by [Jesper Zedlitz](https://www.zedlitz.de/).

The plugin logo is designed by Lakshman Poonyth.
Many thanks for publishing this icon on
[openclipart.org](https://openclipart.org/detail/13039/globe-of-flags-by-anonymous-13039).

Many thanks to the community at the
[CMSimple forum](https://www.cmsimpleforum.com/) for tips, suggestions and testing.

And last but not least many thanks to [Peter Harteg](https://harteg.dk/),
the “father” of CMSimple, and all developers of
[CMSimple_XH](https://www.cmsimple-xh.org/) without whom this amazing CMS
would not exist.
