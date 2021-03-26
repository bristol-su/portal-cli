# Atlas CLI

## Installation

Once we publish releases it'll be a static URL to download the phar file. For now, we should
- Clone this repository `git clone git@github.com:ElbowSpaceUK/atlas-cli`.
- cd into the cloned repository and run `composer install`
- You can now run commands using `php atlas`

## Usage

You can run the atlas command by running `php /path/to/atlas-cli/atlas` (or just `php atlas` if you're in the cli directory).

You can add an alias for `atlas="php /path/to/atlas-cli/atlas"` so atlas can be referenced with just `atlas`. Future references to the cli will be referenced as `atlas`, so replace
this with `php /path/to/atlas-cli/atlas` if you don't set up aliases.

You should always run `atlas setup` after any updates to the atlas command.

### Setting up a new local site

You can create a new site at any time by running `atlas site:new`. You may have any number of sites running at the same time.

You will be asked for a name and description for the site (the description is optional). These can also be passed through
as arguments
`atlas site:new --name="Name for the site" --description="Description for the site"`

By default, the Atlas CMS will be installed. You can instead install the frontend site by passing `--repository=frontend`.

### Deleting an site

To remove an site, just run `atlas site:delete` and choose the site to delete.

### List the sites

You can list the sites you have created by running `atlas site:list`. From here, you can see their status and their URL.

### Working with a site

When you create a new site, this will be the default site for future commands. This saves you having to always choose the site
to run commands against.

To use a different site as the default, run `atlas site:use`. To clear the default, and always be prompted for the site, run `atlas site:clear`

You can see which site is being used as the default by which site has a `*` next to it when running `atlas site:list`.

### Turn on and off a site

You can turn a site on and off by running `atlas site:up` or `atlas site:down`. These will use the default site.

### Resetting a site

This will take the site back to a fresh version. Make sure you save any work before running this.



## Command Reference



### Sites

- Create a site: `atlas site:new`
- Delete a site: `atlas site:delete`
- Bring a site up: `atlas site:up`
- Bring a site down: `atlas site:down`
- See all sites: `atlas site:list`
- Prune sites (if you've deleted one in the filesystem): `atlas site:prune`
- Set a site as the default site: `atlas site:use`
- Clear the current site, so always prompt for the site to use: `atlas site:clear`

### Features

Within a site, you may create features. These are branches off develop which contain current work in progress. Once a feature is complete, it will be merged into develop and the feature can be deleted.

- Create a feature: `atlas feature:new`
- See all features: `atlas feature:list`

### Local modules

- Use a local modules: `atlas dep:local`
