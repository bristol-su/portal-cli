# Atlas CLI

## Installation

### Standard Installation

This will install the Atlas CLI as normal, as a single file that cannot be edited. If you're not sure which option to choose,
this is the one for you!

Download the `atlas` binary from the Releases section on GitHub - https://github.com/ElbowSpaceUK/atlas-cli/releases. We always
recommend using the latest version.

Place this file anywhere in your systems PATH, for example `/usr/local/bin` or `~/.local/bin`. We recommend `~/.local/bin` since this will allow you to use self-update. Give `atlas` permissions to run by running `sudo chmod a+x /path/to/atlas`.

If `~/.local/bin` isn't in your path, you can add it by adding `export PATH="$HOME/.local/bin:$PATH"` to your .bashrc or .bash_profile file in your home directory.

You can now run commands using `atlas`

### Developing for the CLI

If you want to develop for the CLI, you will instead need to clone the `atlas-cli` repository with `git clone git@github.com:ElbowSpaceUK/atlas-cli`. Add an alias in your `.bash_aliases` file (or equivalent), for example `alias atlas-development="/path/to/cloned/atlas-cli/atlas"`. This references the atlas file in the root of the `atlas-cli` repository.

You can now run `atlas-development post-update` to set up the development CLI for the first time (we'd recommend using a different project directory), and any changes made in the atlas cli will be instantly reflected in the `atlas-development` command.

If you'd also like to edit origin-engine, you may clone that repository to your local pc and reference it in the atlas-cli `composer.json` file as a local composer repository to symlink the two.

## Usage

Most of this guide should be covered in the origin-engine [user guide](https://github.com/ElbowSpaceUK/origin-engine/blob/develop/docs/user-notes.md) or the [developer guide](https://github.com/ElbowSpaceUK/origin-engine/blob/develop/docs/developer-notes.md)

You should always run `atlas post-update` after any updates to the atlas command. The first time you run this, you need to give a project directory where all the code will be saved.

### Sharing containers over a local network

To share containers between sites, for example having a single mysql database shared between two apps, you can manually edit your `docker-compose.yml` file. For this to work, you must be using Laravel with Laravel Sail, using the `docker-compose.yml` struture which doesn't deviate too far from the default Sail environment, and must be using the default database credential `.env` variables.

Ensure you NEVER commit the `docker-compose.yml` changes, as these are specific to your setup and will break the site for everyone else.

- Create or decide on the FE and CMS instance to use
- Bring the FE instance down with `./vendor/bin/sail down -v`
- On the FE `docker-compose.yml`, add the following to the `networks` key. `cms-name` is the name of your CMS site sluggified, which is also the folder the CMS is  in.
```
    cms-name_sail:
    driver: bridge
    external: true
```
- Within the `atlas.su.test` service, in the `networks` key, add `-cms-name_sail`.
- Update the `.env.local` variables to do with the database to share the same credentials as the `cms`.
- Bring the FE back up.
- Run `./vendor/bin/sail artisan migrate --env=local` to migrate the FE changes

## Release Process

The general gitflow process applies. Work makes its way to `develop`, and when a release is ready it should go to the release branch. The changelog should be updated, and a review may be completed.

After merging into main, you should run `php ./atlas app:build atlas` in a local checkout of the main branch. The build version should be the version you are about to tag the repository with, beginning with a v (e.g. `v0.1.0`, `v1.2.23` etc). This will produce a build/atlas binary. Do not commit any changes to version control.

You can then create a github release as normal. Upload the binary produced to the release.
