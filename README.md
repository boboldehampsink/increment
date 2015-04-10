Increment plugin for Craft CMS
=================

Plugin that automatically ups each increment field by one number.

Features:
- Able to output the value in the CP and in the Site (no other plugin has this feature)
- Ability to have the value prefixed with text or variables (like {id} or {{now.year}})
- This plugin checks if the calculated next value is still unique on save

Important:
The plugin's folder should be named "increment"

Credits:
This plugin's idea is derived from the Sprout Incremental Plugin by Barrel Strength Design

Changelog
=================
###0.2###
- Added the ability to add a prefix to each value, like {id} or {{now.year}}
- Prevent duplicate values when multiple people create elements at the same time.
- Added MIT License

###0.1###
- Initial push to GitHub