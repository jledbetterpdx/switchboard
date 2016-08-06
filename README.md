# switchboard
This is the source code for the Switchboard Home Automation System, a smart home information kiosk built in PHP, CodeIgniter and jQuery. It was designed for the Raspberry Pi 1 Model B for a specific monitor in my home, so this is to be considered an alpha release; the code needs to be refactored and redesigned to be more responsive.

## Features
* Outdoor weather -- currently taken from [wunderground.com](http://wunderground.com)
  * Current temp, conditions, humidity, rainfall, wind/gusts and pressure
  * 3-hour forecasted temp and conditions
  * Hyperlocal -- uses nearby weather station instead of airport temperatures
* Nest thermostat integration
  * Current temp, mode (including energy-saving mode), humidity and target temp + time
  * Uses unofficial thermostat API -- recent intermittent unexplained outages
* News ticker -- currently taken from [Reuters' U.S. news feed](http://feeds.reuters.com/Reuters/domesticNews)
* Current date and time
* Sunrise and sunset from PHP built-in functions
* Moon phase -- currently taken from the [Aeris weather API](http://www.aerisweather.com/develop/)
* Above served using AJAX
* Updates at a randomly chosen interval between 4 and 6 minutes, to not flood APIs with requests at regular intervals and ensure relatively up-to-date data
* Auto-recovery if an error happens when calling for data + offline indicator
* Auto-dimming at night based on civil dawn/dusk

## Short Term To-Do
* Switch to official Nest API
  * May fix disappearing target temp time bug
* The 3 R's -- **R**efactor, **R**efactor, **R**efactor
* Make responsive -- less fixed design, more mobile support
* Add upcoming TV episode schedule to ticker
* Add daily forecast alongside 3-hour forecast
* Redesign barometric pressure to place arrows to right of value
* Redo offline/recovery indicator to be less obtrusive, show a retry countdown, and retain stale data for longer

## Long Term To-Do
* Add touch events to allow for control of Nest from kiosk
* Nest Protect integration
* Rudimentary admin controls for changing units, weather station, news feed etc.
