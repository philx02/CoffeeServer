CXXFLAGS += -fPIC
CFLAGS   += -fPIC
LDFLAGS   = -shared

TARGET_DIR=../lib/$(TARGET_ARCH)
EXECUTABLE=libCoffeeTransact.so
TARGET=$(TARGET_DIR)/$(EXECUTABLE)

MKDIR_P = mkdir -p

rwildcard=$(wildcard $1$2) $(foreach d,$(wildcard $1*),$(call rwildcard,$d/,$2))
SOURCES := $(call rwildcard,src/,*.cpp)
SOURCES_C := $(call rwildcard,src/,*.c)
OBJDIRS := $(patsubst src/%,obj/$(TARGET_ARCH)/%,$(SOURCES))
OBJDIRS += $(patsubst src/%,obj/$(TARGET_ARCH)/%,$(SOURCES_C))
OBJECTS := $(patsubst src/%.cpp,obj/$(TARGET_ARCH)/%.o,$(SOURCES))
OBJECTS += $(patsubst src/%.c,obj/$(TARGET_ARCH)/%.o,$(SOURCES_C))
DEPS := $(OBJECTS:.o=.d)
		
.PHONY: directories

all: directories $(TARGET)

directories: $(OBJDIRS)

$(OBJDIRS):
		@$(MKDIR_P) $(OBJDIRS)

$(TARGET): $(OBJECTS)
		@$(MKDIR_P) $(TARGET_DIR)
		@echo "=== Linking ===" $@
		@$(CXX) $(LDFLAGS) $(OBJECTS) -o $@

obj/$(TARGET_ARCH)/%.o: src/%.cpp
		@echo "== Compiling ==" $<
		@$(CXX) $(CXXFLAGS) -MD -c $< -o $@

obj/$(TARGET_ARCH)/%.o: src/%.c
		@echo "== Compiling ==" $<
		@$(CC) $(CFLAGS) -MD -c $< -o $@

-include $(DEPS)

clean:
		@rm -rf $(OBJECTS) $(TARGET) $(DEPS)

rebuild: clean all
