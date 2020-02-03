FROM mysql:5.7

ARG HOST_UID

# Run updates and init db
ADD init.sql /docker-entrypoint-initdb.d/

# Disable unnecessary sql modes
RUN echo 'sql-mode="STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"' >> /etc/mysql/conf.d/docker.cnf

# Change usermod for mysql
RUN usermod -u ${HOST_UID} mysql && groupmod -g ${HOST_UID} mysql

# Mount volume
VOLUME ["/var/lib/mysql"]

EXPOSE 3306
