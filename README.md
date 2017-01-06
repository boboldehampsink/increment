Increment plugin for Craft CMS
=================

Plugin that automatically ups each increment field by one number.

Features:
- Able to output the value in the CP and in the Site (no other plugin has this feature)
- Ability to have the value prefixed with text or variables (like {id} or {{now.year}})
- Ability to reset increment yearly
- Ability to zero pad the value
- This plugin checks if the calculated next value is still unique on save

Important:
The plugin's folder should be named "increment"

Credits:
This plugin's idea is derived from the Sprout Incremental Plugin by Barrel Strength Design

Changelog
=================
###0.3.3###
- Fixed setting of postDate on fresh entries

###0.3.2###
- Fixed bug where yearly reset looked at the wrong date (dateCreated in stead of postDate)

###0.3.1###
- Fixed bug where padding was not a number (thanks to @steverowling)
- Fixed bug where postDate was assumed for all Element Types (thanks to @steverowling)

###0.3.0###
- Refactor to only recalculate numbers on save when entry is a new record
- Added the ability to reset increment yearly

###0.2.2###
- Added the ability to control number recalculation on save

###0.2.1###
- Added the ability to use the then yet non-existing postDate in prefix like in Craft's Live Preview

###0.2###
- Added the ability to add a prefix to each value, like {id} or {{now.year}}
- Added zero padding options
- Prevent duplicate values when elements are created at the same time.
- Many small improvements and code cleanups
- Added MIT License

###0.1###
- Initial push to GitHub
