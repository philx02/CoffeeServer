#pragma once

#ifdef _WIN32

#ifdef COFFEETRANSACT_EXPORTS
#define COFFEETRANSACT_API __declspec(dllexport)
#else
#define COFFEETRANSACT_API __declspec(dllimport)
#endif

#else

#define COFFEETRANSACT_API

#endif