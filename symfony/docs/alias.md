## コマンドの別名

```
echo "alias ll='ls -l'" >> ~/.bashrc
echo "alias la='ls -la'" >> ~/.bashrc
echo "alias dev='php bin/console --env=dev'" >> ~/.bashrc
echo "alias prod='php bin/console --env=prod'" >> ~/.bashrc
echo "alias cc='rm -rf var/cache/*;rm -rf var/logs/*;rm -rf var/sessions/*;chmod 777 -R var/cache;chmod 777 -R var/logs;chmod 777 -R var/sessions'" >> ~/.bashrc
source ~/.bashrc
```
