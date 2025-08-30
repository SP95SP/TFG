#!/bin/bash
BASHRC="$1"
LOG_LINE='echo "$(date +'\''%F %T'\'') | Usuario actual: $USER | Usuario anterior: $SUDO_USER" >> /var/log/access.log'
grep -qxF "$LOG_LINE" "$BASHRC" || echo "$LOG_LINE" >> "$BASHRC"
