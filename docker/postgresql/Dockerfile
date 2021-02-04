FROM postgres:11

ARG HOST_UID

# Run updates and init db
COPY conf/000-init.sql /docker-entrypoint-initdb.d/

VOLUME ["/var/lib/postgresql/data"]

RUN usermod -u ${HOST_UID} postgres
RUN groupmod -g ${HOST_UID} postgres

EXPOSE 5432
