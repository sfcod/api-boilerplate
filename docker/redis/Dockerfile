FROM redis:5

ARG HOST_UID

# Change usermod for redis
RUN usermod -u ${HOST_UID} redis && groupmod -g ${HOST_UID} redis

# Mount volume
VOLUME ["/data"]

EXPOSE 6379
