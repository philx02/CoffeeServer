PROJECTS := CoffeeTransact CoffeeDaemon

.PHONY: all $(PROJECTS)

all: $(PROJECTS)

CoffeeDaemon: CoffeeTransact
		@echo "=== CoffeeDaemon ==="
		@$(MAKE) -C CoffeeDaemon --no-print-directory

CoffeeTransact:
		@echo "=== CoffeeTransact ==="
		@$(MAKE) -C CoffeeTransact --no-print-directory

clean:
		@for subdir in $(PROJECTS); do \
			$(MAKE) -C $$subdir clean || exit 1; \
		done

rebuild: clean all

tx: all
		@scp bin/$(TARGET_ARCH)/*.exe $(scpRaspberry)
		@scp lib/$(TARGET_ARCH)/*.so $(scpRaspberry)
