# ACF Custom Field Location Rules

Set ACF field group location based on select, checkbox, radio and true/false fields in other field groups.

## How to use

Create a field group with choice fields.

When adding additional field groups the choice fields in other field groups will be detected 
and be available to chooose from.

* Only top level choice fields are available.
* Repeaters and flexible content sub fields cannot be used.
* The field that you are basing your location rule on must be in a field group on the same post. Any choice field can be selected but if it's not part of another field group on the same post then it woun't do anything.

## Issues with the plugin

It will slow down the loading of admin pages. Every choice field will trigger ACF run AJAX to check for
field groups to be shown. Worse than this, it will trigger this action for every choice in a radio or
checkbox field.