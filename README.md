# Portal CLI

## Installation

### Standard Installation

This will install the Portal CLI as normal, as a single file that cannot be edited. If you're not sure which option to choose,
this is the one for you!

Download the `portal` binary from the Releases section on GitHub - https://github.com/bristol-su/portal-cli/releases. We always
recommend using the latest version.

Place this file anywhere in your systems PATH, for example `/usr/local/bin` or `~/.local/bin`. We recommend `~/.local/bin` since this will allow you to use self-update. Give `portal` permissions to run by running `sudo chmod a+x /path/to/portal`.

If `~/.local/bin` isn't in your path, you can add it by adding `export PATH="$HOME/.local/bin:$PATH"` to your .bashrc or .bash_profile file in your home directory.

You can now run commands using `portal`

### Developing for the CLI

If you want to develop for the CLI, you will instead need to clone the `portal-cli` repository with `git clone git@github.com:bristol-su/portal-cli`. Add an alias in your `.bash_aliases` file (or equivalent), for example `alias portal-development="/path/to/cloned/portal-cli/portal"`. This references the portal file in the root of the `portal-cli` repository.

You can now run `portal-development post-update` to set up the development CLI for the first time (we'd recommend using a different project directory), and any changes made in the portal cli will be instantly reflected in the `portal-development` command.

If you'd also like to edit origin-engine, you may clone that repository to your local pc and reference it in the portal-cli `composer.json` file as a local composer repository to symlink the two.

## Usage

Most of this guide should be covered in the origin-engine [user guide](https://github.com/ElbowSpaceUK/origin-engine/blob/develop/docs/user-notes.md) or the [developer guide](https://github.com/ElbowSpaceUK/origin-engine/blob/develop/docs/developer-notes.md)

You should always run `portal post-update` after any updates to the portal command. The first time you run this, you need to give a project directory where all the code will be saved.

## Release Process

The general gitflow process applies. Work makes its way to `develop`, and when a release is ready it should go to the release branch. The changelog should be updated, and a review may be completed.

After merging into main, you should run `php ./portal app:build portal` in a local checkout of the main branch. The build version should be the version you are about to tag the repository with, beginning with a v (e.g. `v0.1.0`, `v1.2.23` etc). This will produce a build/portal binary. Do not commit any changes to version control.

You can then create a github release as normal. Upload the binary produced to the release.
