sudo vi /etc/cron.d/symfony

*/10 * * * * vagrant cp -rp /dev/shm/symfony/cache /vagrant/symfony/var/cache
*/10 * * * * vagrant cp -rp /dev/shm/symfony/logs /vagrant/symfony/var/logs
