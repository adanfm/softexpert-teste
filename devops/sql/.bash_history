pg_dump -U softexpert -h localhost -p test_softexpert\ > backup_file
pg_dump -U softexpert -h localhost -p test_softexpert > backup_file
pg_dump -U softexpert -h localhost -W test_softexpert > backup_file
ls
cat backup_file 
pg_dump -U softexpert -h localhost -W test_softexpert --no-owner > backup_file.sql
pg_dump -U softexpert -h localhost -W test_softexpert --no-owner --no-privileges > backup_file.sql
ls
mv backup_file.sql ./root/
exit
ls
cd /root
ls
pg_dump --username=softexpert --password=softexpert123 --no-privileges --no-owner
pg_dump --username=softexpert --password=softexpert123 --no-privileges --no-owner
pg_dump --username=softexpert --password=softexpert123 --no-privileges --no-owner
pg_dump --username=softexpert --password=softexpert123 --no-privileges --no-ownergexit
exit
