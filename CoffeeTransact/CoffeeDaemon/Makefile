CXXFLAGS += -I../CoffeeTransact/include
LDFLAGS   = -L../lib/$(TARGET_ARCH) -L$(BOOST_ROOT)/lib -lCoffeeTransact -lboost_system -lpthread -ldl

TARGET_DIR=../bin/$(TARGET_ARCH)
EXECUTABLE=CoffeeDaemon.exe
TARGET=$(TARGET_DIR)/$(EXECUTABLE)

MKDIR_P = mkdir -p

rwildcard=$(wildcard $1$2) $(foreach d,$(wildcard $1*),$(call rwildcard,$d/,$2))
SOURCES := $(call rwildcard,src/,*.cpp)
OBJDIRS := $(patsubst src/%,obj/$(TARGET_ARCH)/%,$(SOURCES))
OBJECTS := $(patsubst src/%.cpp,obj/$(TARGET_ARCH)/%.o,$(SOURCES))
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

-include $(DEPS)

clean:
		@rm -rf $(OBJECTS) $(TARGET) $(DEPS)

rebuild: clean all
