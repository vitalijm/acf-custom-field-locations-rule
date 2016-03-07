# ACF Custom Field Location Rules

Set ACF field group location based on select, checkbox, radion and true/false fields in other field groups.

I'm not sure if this plugin will be supported or has a future, it has some issues that I'm pretty sure that
I cannot address or that cannot be solved (see below). I did it mostly to see if I could and I usually plan
development so that these types of location rules are not needed. The best solution would be that something
along the lines of this plugin are incorporated into ACF because that's likely the only way the the issues
will be resolved.

## How to use

Create a field group with choice fields. When additional field groups the choice fields in other field
groups will be detected and be available to chooose from.

* Only top level choice fields are available.
* Repeaters and flexible content sub fields cannot be used.

## Issues with the plugin

It will slow down the loading of admin pages. Every choice field will trigger ACF run AJAX to check for
field groups to be shown. Worse than this, it will trigger this action for every choice in a radio or
checkbox field.

Any page with a choice field will be dirty. Meaning that ACF will thing that a change has been made even
when no change is made and will always ask if you're sure you want to navigate away from the page.