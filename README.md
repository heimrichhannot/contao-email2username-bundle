# Contao Email2Username Bundle

[![](https://img.shields.io/packagist/v/heimrichhannot/contao-email2username-bundle.svg)](https://packagist.org/packages/heimrichhannot/contao-email2username-bundle)
[![](https://img.shields.io/packagist/dt/heimrichhannot/contao-email2username-bundle.svg)](https://packagist.org/packages/heimrichhannot/contao-email2username-bundle)

A [Contao](https://contao.org) extension that allows using the e-mail address as username for members and users.


## Features

- login with username or e-mail address
- set username to e-mail address on registration, member/user creation or member/user update (can be disabled)
- can be only activated for members,
- can be deactivated for user and/or member (see Configuration chapter)

## Install & Setup

1. Install from composer or Contao Manager

        composer require heimrichhannot/contao-email2username-bundle
 
2. Customize config to your needs (e.g. enable only for backend user), see Configuration.

## Configuration

```yaml
# Default configuration for extension with alias: "huh_email2username"
huh_email2username:
   member:

      # Enable support for frontend member.
      enable:               true

      # Allow override existing usernames.
      override:             true
   user:

      # Enable support for backend user.
      enable:               true

      # Allow override existing usernames.
      override:             true

```

