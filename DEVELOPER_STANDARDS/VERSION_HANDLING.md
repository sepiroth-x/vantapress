# Version Handling Standards

**Last Updated:** December 4, 2025

---

## Version Control Rules

### 1. Repository Push Policy

**CRITICAL:** No pushing to repository without explicit command.

- ❌ **Never auto-push** changes to any branch
- ❌ **Never assume** it's time to push
- ✅ **Wait for explicit instruction** from the developer
- ✅ **Confirm** before executing any git push command

**Why this matters:**
- Prevents accidental commits
- Allows developer to review all changes
- Maintains control over release timing
- Ensures quality before publishing

---

### 2. Version Numbering Format

**Format:** `v.X.Y.Z-complete`

**Structure:**
- `v.` - Version prefix
- `X` - Major version (breaking changes)
- `Y` - Minor version (new features, backwards compatible)
- `Z` - Patch version (bug fixes, minor improvements)
- `-complete` - Release suffix (indicates deployment-ready)

**Examples:**
- `v.1.0.0-complete` - Initial release
- `v.1.0.1-complete` - Bug fix release
- `v.1.1.0-complete` - New feature release
- `v.2.0.0-complete` - Major version with breaking changes

**The `-complete` Suffix:**
- Indicates the release is **production-ready**
- All features tested and working
- Documentation updated
- Deployment package prepared
- Ready for shared hosting deployment

---

### 3. Version Increment Rules

**MANDATORY:** Version numbers **must be updated/incremented** every time changes are pushed to the release branch.

#### When to Increment

**Patch Version (Z):**
- Bug fixes
- Security patches
- Minor documentation updates
- Performance improvements
- No new features or breaking changes

**Minor Version (Y):**
- New features added
- New modules or themes
- Enhancements to existing functionality
- Backwards compatible changes
- Reset patch version to 0

**Major Version (X):**
- Breaking changes
- Major architectural changes
- Database schema changes requiring migration
- API changes that break compatibility
- Complete redesigns
- Reset minor and patch versions to 0

#### Where to Update Version Numbers

Before pushing to release branch, update version in:

1. **`config/version.php`**
   ```php
   return [
       'version' => '1.0.0',
       'release_date' => '2025-12-03',
       'codename' => 'complete'
   ];
   ```

2. **`RELEASE_NOTES.md`**
   ```markdown
   # 🚀 VantaPress - Release Notes
   
   **Current Version:** v1.0.0-complete
   **Release Date:** December 3, 2025
   ```

3. **`DEVELOPMENT_GUIDE.md`**
   ```markdown
   **Version:** 1.0.0
   **Last Updated:** December 3, 2025
   ```

4. **`README.md`**
   ```markdown
   ![Version](https://img.shields.io/badge/version-v1.0.0--complete-blue)
   ```

5. **Git Tag**
   ```bash
   git tag -a v1.0.0-complete -m "Release v1.0.0-complete"
   git push origin v1.0.0-complete
   ```

---

## Release Workflow

### Pre-Release Checklist

Before incrementing version and pushing to release:

- [ ] All features tested and working
- [ ] Documentation updated
- [ ] `SESSION_DEV_HANDLING.md` updated with session summary
- [ ] Version numbers updated in all files
- [ ] `RELEASE_NOTES.md` includes changelog
- [ ] Deployment package tested
- [ ] No critical bugs or issues
- [ ] Developer explicitly approved push

### Release Branch Push Process

```bash
# 1. Update version numbers in all required files (manual)

# 2. Commit version changes
git add .
git commit -m "Bump version to v1.0.0-complete"

# 3. Create and push tag
git tag -a v1.0.0-complete -m "Release v1.0.0-complete"
git push origin main
git push origin v1.0.0-complete

# 4. Update SESSION_DEV_HANDLING.md (if approved by developer)
```

---

## Version History Reference

**Current Version:** v1.0.18-complete

**Version Timeline:**
- v1.0.18 - Page creation and media upload bug fixes
- v1.0.17 - Admin footer with developer attribution
- v1.0.14 - Villain-themed installer UI & developer standards
- v1.0.13 - WordPress-style auto-updates system
- v1.0.12 - Theme-based admin styling architecture
- v1.0.11 - Filament admin panel styling fixes
- v1.0.10 - Simple HTML welcome page solution
- v1.0.9 - Enhanced APP_KEY detection
- v1.0.8 - Pre-boot APP_KEY check
- v1.0.7 - Pre-installation UX improvement
- v1.0.6 - APP_KEY auto-generation fix
- v1.0.5 - Theme screenshot display
- v1.0.0 - Initial public release

---

## Summary

**Three Golden Rules:**

1. ⛔ **No pushing without explicit command** - Always wait for approval
2. 📦 **Format: v.X.Y.Z-complete** - Use standard versioning format
3. ⬆️ **Increment on every release push** - Never push with same version number

**Current Project Version:** v1.0.14-complete

**Remember:** Version control is critical for tracking changes, deployment history, and user support. Always follow these standards to maintain project integrity.

---

**Author:** Sepiroth X Villainous (Richard Cebel Cupal, LPT)  
**Project:** VantaPress CMS  
**License:** MIT
