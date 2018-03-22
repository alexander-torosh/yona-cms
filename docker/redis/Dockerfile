FROM redis:4.0-alpine

RUN  echo "vm.overcommit_memory = 1" >> /etc/sysctl.conf

CMD ["redis-server"]