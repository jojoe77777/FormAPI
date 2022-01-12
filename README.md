# FormAPI

Simple API for creating forms for MCPE clients (PocketMine only)

## Including in other plugins

### As a plugin
This library can be loaded as a plugin phar. You can use the [`depend`](https://doc.pmmp.io/en/rtfd/developer-reference/plugin-manifest.html#depend) key in `plugin.yml` to require its presence.

### As a virion
This library supports being included as a [virion](https://github.com/poggit/support/blob/master/virion.md).

If you use [Poggit](https://poggit.pmmp.io) to build your plugin, you can add it to your `.poggit.yml` like so:

```yml
projects:
  YourPlugin:
    libs:
      - src: jojoe77777/FormAPI/libFormAPI
        version: ^2.1.0
```
