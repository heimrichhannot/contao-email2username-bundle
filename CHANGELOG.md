# Changelog
All notable changes to this project will be documented in this file.

## [1.2.0] - 2020-06-11
- added disable_override_existing_usernames option to keep existing user and member names
- fixed wrong option was evaluated in createNewsUser hook
- code enhancements

## [1.1.0] - 2020-05-06

- added createNewUser hook for the registration module
- added enabled checks for the hook listener
- fixed code fixer file

## [1.0.1] - 2019-10-09

### Changed
- changed minimum contao version to 4.4

## [1.0.0] - 2019-10-09

### Changed
- BREAKING: change config from tl_settings to yaml
- some refactoring to current symfony and internal standards
- bundle now replaces the contao 3 model via composer.json

## [0.3.1] - 2018-10-19

### Removed
- contao-utils-bundle dep

## [0.3.0] - 2018-08-06

### Added
- possibility to deactivate for members or users

## [0.2.0] - 2018-08-02

### Fixed
- setMembernameFromEmail

#### Added
- gitignore

## [0.1.0] - 2018-04-04

- initial commit
- moved module to bundle structure
- refactored classes to services
