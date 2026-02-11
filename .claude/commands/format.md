---
description: Format, test, and prepare for a Git push
---

1. Run `composer run format`
2. Run `composer run phpstan`
3. If phpstan triggers a warning, fix the issue. Re-run to confirm success.
4. Run `pest`. If test fail, fix test and report change. Re-run `composer run format` once suit return green.
