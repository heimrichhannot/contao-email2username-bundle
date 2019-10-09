# Contao Email2Username Bundle

[![](https://img.shields.io/packagist/v/heimrichhannot/contao-email2username-bundle.svg)](https://packagist.org/packages/heimrichhannot/contao-email2username-bundle)
[![](https://img.shields.io/packagist/dt/heimrichhannot/contao-email2username-bundle.svg)](https://packagist.org/packages/heimrichhannot/contao-email2username-bundle)

A [Contao](https://contao.org) bundle that automatically sets the username based on the user email address.


## Features

- disables the username field and sets the name to the given user e-mail address
- works for members and users
- case-insensitive email login within tl_member & tl_user
- can be deactivated for user and/or member (see Configuration chapter)

## Install

```
composer require heimrichhannot/contao-email2username-bundle
```

## Configuration

You can disable this bundles functionality for member or user in your config files (typically [Project-Folder]/app/config/config.yml). By default, it is activated for both.

```yaml
huh_email2username:
    user: true # Enable or disable for tl_user. Default: Enabled (true)
    memeber: true # Enable or disable for tl_member. Default: Enabled (true)
```

