FROM bitnami/laravel:9@sha256:0b5bc17f3f0b3fbb178eba943d558c3daf93bcdf86e1a261e1eedacd22c23b1e

# Install necessary packages
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-scripts

COPY . .

# Add and make the run.sh script executable
COPY run.sh /app/run.sh
RUN chmod +x /app/run.sh

# Use the custom run.sh as the entrypoint
ENTRYPOINT ["/app/run.sh"]
