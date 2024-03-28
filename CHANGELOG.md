# Changelog
All notable changes to this project will be documented in this file.

## [1.5.0] - 2024-03-28
- Added: Support for Contao 5

## [1.4.0] - 2022-02-14

- Fixed: config for symfony 4+
- Changed: minimum contao version is now 4.9

## [1.3.0] - 2021-08-30

- Added: php8 support

## [1.2.6] - 2020-12-11
- fixed issue when Model is multilingual using DC_Multilingual

## [1.2.5] - 2020-09-15
- added missing dependency to utils-bundle (thanks @nano-67, see https://github.com/heimrichhannot/contao-email2username-bundle/issues/2)

## [1.2.4] - 2020-09-15
- removed Module parameter type in CreateNewUserListener to avoid exception in combination with some legacy code

## [1.2.3] - 2020-08-05
- fixed empty password warning when username field no activated in registration form (see https://github.com/contao/contao/issues/1809)

## [1.2.2] - 2020-06-26
- fixed wrong boolean comparison

## [1.2.1] - 2020-06-19
- fixed type error

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
