#pragma once

#ifdef COFFEETRANSACT_EXPORTS
#define COFFEETRANSACT_API __declspec(dllexport)
#else
#define COFFEETRANSACT_API __declspec(dllimport)
#endif
