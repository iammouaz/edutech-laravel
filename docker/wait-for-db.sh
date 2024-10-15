# Wait until MySQL is available
echo "Waiting for MySQL to be available..."

while ! nc -z db 3306; do
  sleep 1
done

echo "MySQL is up - executing command"

# Run the provided CMD command
exec "$@"
