# Atlas CLI

## Installation

Once we publish releases it'll be a static URL to download the phar file. For now, we should
- Clone this repository `git clone git@github.com:ElbowSpaceUK/atlas-cli`.
- cd into the cloned repository and run `composer install`
- You can now run commands using `php atlas`

## Usage

Add an alias for `atlas="php /full/path/to/cloned/repo/atlas"` so atlas can be referenced with just `atlas`.

You should always run `atlas setup` after any updates to the atlas command.

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
