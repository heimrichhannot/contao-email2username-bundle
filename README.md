# Contao Email2Username Bundle

[![](https://img.shields.io/packagist/v/heimrichhannot/contao-email2username-bundle.svg)](https://packagist.org/packages/heimrichhannot/contao-email2username-bundle)
[![](https://img.shields.io/packagist/dt/heimrichhannot/contao-email2username-bundle.svg)](https://packagist.org/packages/heimrichhannot/contao-email2username-bundle)

A [Contao](https://contao.org) bundle that automatically sets the username based on the user email address.


## Features

- disables the username field and sets the name to the given user e-mail address
- works for members and users
- case-insensitive email login within tl_member & tl_user
- can be deactivated for user and/or member (see Configuration chapter)

## Install & Setup

1. Install from composer or Contao Manager

        composer require heimrichhannot/contao-email2username-bundle
 
1. Customize config to your needs (e.g. enable only for backend user), see Configuration.

1. If you want only new members/users to get email as username, enable `disable_override_existing_usernames` option.


## Configuration

You can disable this bundles functionality for member or user in your config files (typically [Project-Folder]/app/config/config.yml). By default, it is activated for both.

```yaml
huh_email2username:

    # Enable support for backend user.
    user:                 true

    # Enable support for frontend member.
    member:               true

    # Disable overriding existing usernames.
    disable_override_existing_usernames: false
```

