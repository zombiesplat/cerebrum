# Frametest Interview Test

In your web browser, navigate to <http://localhost:8100> to start the example.

For the bonus page, navigate to http://localhost:8001/addition

## Setup

	./haz setup
	./haz start

To execute the test suite: `./haz test`


## Caveats

Symfony likes to create a cache that can cause the tests or the site to not respond correctly,
to correct this, please delete the `/web/code/cache/container.php` file

