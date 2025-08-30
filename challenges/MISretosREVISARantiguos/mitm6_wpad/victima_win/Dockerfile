FROM debian:stable-slim

RUN apt-get update && \
    apt-get install -y curl dnsutils iputils-ping net-tools

COPY simulate_wpad.sh /simulate_wpad.sh
RUN chmod +x /simulate_wpad.sh

CMD ["/simulate_wpad.sh"]
