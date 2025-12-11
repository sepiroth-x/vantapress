# 🚨 [MUST READ BEFORE PUSHING] DEPLOYMENT RULINGS

**CRITICAL:** This file MUST be read and followed EVERY time before pushing to the release branch!

---

## 📋 PRE-DEPLOYMENT CHECKLIST

### ✅ STEP 1: Determine Next Version Number

**Current Version Check:**
1. Read `config/version.php` to see current version
2. Determine increment type:
   - **Patch** (x.x.X): Bug fixes, minor improvements, documentation updates
   - **Minor** (x.X.0): New features, significant enhancements
   - **Major** (X.0.0): Breaking changes, major architecture changes

**Version Format MUST BE:** `x.x.x-complete`
- ✅ Examples: `1.1.3-complete`, `1.2.0-complete`, `2.0.0-complete`
- ❌ NEVER: `1.1.3`, `v1.1.3`, `1.1.3-beta`

### ✅ STEP 2: Update ALL Version Files

**MANDATORY - Update these files in this order:**

#### 1. `config/version.php` (SOURCE OF TRUTH)
```php
'version' => env('APP_VERSION', '1.1.4-complete'), // ← UPDATE THIS
```

#### 2. `.env.example` (Reference for new installations)
```
APP_VERSION=1.1.4-complete  # ← UPDATE THIS
```

#### 3. `index.html` (Landing page version display)
```html
<span class="version-badge">v1.1.4-complete</span>  <!-- ← UPDATE THIS -->
```

#### 4. `README.md` (Repository documentation)
```markdown
**Current Version:** v1.1.4-complete  <!-- ← UPDATE THIS -->
```

#### 5. `RELEASE_NOTES.md` (Complete release documentation)
```markdown
**Current Version:** v1.1.4-complete  <!-- ← UPDATE THIS -->
**Release Date:** December 8, 2025  <!-- ← UPDATE THIS -->

## 📌 Latest Version: v1.1.4-complete - [Feature Name]  <!-- ← UPDATE THIS -->
```

**IMPORTANT:** Move previous "Latest Version" section to "Previous Version" section!

### ✅ STEP 3: Verify Version Consistency

**Run this check BEFORE committing:**

```powershell
# Check all version references
Select-String -Path "config/version.php",".env.example","index.html","README.md","RELEASE_NOTES.md" -Pattern "1\.\d+\.\d+-complete"
```

**Expected Result:** ALL files must show the SAME new version number!

### ✅ STEP 4: Update RELEASE_NOTES.md Properly

**Template for new release section:**

```markdown
## 📌 Latest Version: v1.1.4-complete - [Brief Feature Description]

### 🎯 [What This Release Does]

[Detailed explanation of changes]

#### 🆕 What's New

- ✅ [Feature/fix 1]
- ✅ [Feature/fix 2]
- ✅ [Feature/fix 3]

#### 🔧 Technical Details

[Implementation details, code changes, architecture]

#### 🚀 Deployment Instructions

1. Deploy v1.1.4-complete files
2. [Specific deployment steps]

#### ✅ What This Fixes/Adds

From previous version:
- ✅ [Issue 1 fixed]
- ✅ [Feature 1 added]

---

## 📌 Previous Version: v1.1.3-complete - [Previous Feature]
```

**CRITICAL:** Always move the old "Latest Version" section down and rename it to "Previous Version"!

---

## 🚀 DEPLOYMENT WORKFLOW

### Phase 1: Preparation
```powershell
# 1. Ensure you're on development branch
git checkout development

# 2. Make sure all changes are committed
git status  # Should show "nothing to commit, working tree clean"

# 3. Verify current version
Get-Content config/version.php | Select-String "version"
```

### Phase 2: Version Update
```powershell
# 1. Update ALL version files (see STEP 2 above)

# 2. Verify all files updated
Select-String -Path "config/version.php",".env.example","index.html","README.md","RELEASE_NOTES.md" -Pattern "1\.\d+\.\d+-complete"

# 3. Review changes
git diff
```

### Phase 3: Commit & Push Development
```powershell
# 1. Stage all version changes
git add config/version.php .env.example index.html README.md RELEASE_NOTES.md

# 2. Commit with descriptive message including version
git commit -m "Release v1.1.4-complete - [Brief Feature Description]"

# 3. Push to development first
git push origin development
```

### Phase 4: Merge to Release & Tag

**DISPLAY VERSION BEFORE PUSHING:**
```powershell
# Extract and display version
$version = (Get-Content config/version.php | Select-String "env\('APP_VERSION',\s*'([^']+)'").Matches.Groups[1].Value
Write-Host "`n🚀 PUSHING VERSION: v$version`n" -ForegroundColor Green
```

**Then proceed with merge:**
```powershell
# 1. Switch to release branch
git checkout release

# 2. Merge from development
git merge development -m "Merge: Release v1.1.4-complete"

# 3. Create annotated git tag
git tag -a v1.1.4-complete -m "Release v1.1.4-complete - [Brief Feature Description]"

# 4. Push release branch AND tags
git push origin release --tags

# 5. Return to development
git checkout development
```

---

## ⚠️ CRITICAL RULES

### 🔴 NEVER DO THESE:

1. ❌ **NEVER push to release without updating version numbers**
2. ❌ **NEVER use version format without `-complete` suffix**
3. ❌ **NEVER update only some files - ALL or NOTHING**
4. ❌ **NEVER skip the version display before pushing**
5. ❌ **NEVER forget to move "Latest Version" to "Previous Version" in RELEASE_NOTES.md**
6. ❌ **NEVER commit directly to release branch**
7. ❌ **NEVER forget to create git tag**
8. ❌ **NEVER push without `--tags` flag**

### ✅ ALWAYS DO THESE:

1. ✅ **ALWAYS read this file before deployment**
2. ✅ **ALWAYS update ALL 5 version files**
3. ✅ **ALWAYS verify version consistency before committing**
4. ✅ **ALWAYS display version before pushing to release**
5. ✅ **ALWAYS commit to development first**
6. ✅ **ALWAYS merge development → release**
7. ✅ **ALWAYS create annotated git tag**
8. ✅ **ALWAYS push with --tags flag**
9. ✅ **ALWAYS return to development branch after release**
10. ✅ **ALWAYS update RELEASE_NOTES.md with complete details**

---

## 📊 VERSION TRACKING

### Version History Reference:
- `v1.1.3-complete` - Enhanced Fix Script UI Visibility (December 8, 2025)
- `v1.1.2-complete` - Automatic Role Seeding (December 8, 2025)
- `v1.1.1-complete` - TheVillainTerminal Module (Previous)

### Version Increment Guidelines:

**Patch (x.x.X):**
- Bug fixes
- Documentation updates
- UI/UX improvements
- Minor enhancements
- Security patches

**Minor (x.X.0):**
- New features
- New modules
- Significant enhancements
- New admin pages
- Architecture improvements

**Major (X.0.0):**
- Breaking changes
- Complete system overhauls
- Major architecture changes
- Database structure changes requiring manual intervention

---

## 🔍 PRE-PUSH VERIFICATION SCRIPT

**Run this PowerShell script before EVERY deployment:**

```powershell
# VantaPress Pre-Deployment Verification Script

Write-Host "`n🔍 VantaPress Pre-Deployment Verification`n" -ForegroundColor Cyan

# Extract version from config/version.php
$versionContent = Get-Content "config/version.php" -Raw
if ($versionContent -match "'version'\s*=>\s*env\('APP_VERSION',\s*'([^']+)'") {
    $configVersion = $matches[1]
    Write-Host "✅ config/version.php: $configVersion" -ForegroundColor Green
} else {
    Write-Host "❌ config/version.php: VERSION NOT FOUND!" -ForegroundColor Red
    exit 1
}

# Check .env.example
$envExample = Get-Content ".env.example" | Select-String "APP_VERSION="
if ($envExample -match "APP_VERSION=(.+)") {
    $envVersion = $matches[1].Trim()
    if ($envVersion -eq $configVersion) {
        Write-Host "✅ .env.example: $envVersion" -ForegroundColor Green
    } else {
        Write-Host "❌ .env.example: $envVersion (MISMATCH!)" -ForegroundColor Red
    }
}

# Check index.html
$indexHtml = Get-Content "index.html" -Raw
if ($indexHtml -match 'version-badge[^>]*>v?([^<]+)<') {
    $indexVersion = $matches[1].Trim()
    if ($indexVersion -eq $configVersion) {
        Write-Host "✅ index.html: v$indexVersion" -ForegroundColor Green
    } else {
        Write-Host "❌ index.html: v$indexVersion (MISMATCH!)" -ForegroundColor Red
    }
}

# Check README.md
$readme = Get-Content "README.md" | Select-String "Current Version:"
if ($readme -match "v?(\d+\.\d+\.\d+-complete)") {
    $readmeVersion = $matches[1]
    if ($readmeVersion -eq $configVersion) {
        Write-Host "✅ README.md: v$readmeVersion" -ForegroundColor Green
    } else {
        Write-Host "❌ README.md: v$readmeVersion (MISMATCH!)" -ForegroundColor Red
    }
}

# Check RELEASE_NOTES.md
$releaseNotes = Get-Content "RELEASE_NOTES.md" | Select-String "Current Version:" | Select-Object -First 1
if ($releaseNotes -match "v?(\d+\.\d+\.\d+-complete)") {
    $releaseVersion = $matches[1]
    if ($releaseVersion -eq $configVersion) {
        Write-Host "✅ RELEASE_NOTES.md: v$releaseVersion" -ForegroundColor Green
    } else {
        Write-Host "❌ RELEASE_NOTES.md: v$releaseVersion (MISMATCH!)" -ForegroundColor Red
    }
}

# Final verdict
Write-Host "`n🚀 PUSHING VERSION: v$configVersion`n" -ForegroundColor Yellow -BackgroundColor DarkGreen

# Check git status
Write-Host "`n📋 Git Status:" -ForegroundColor Cyan
git status --short

Write-Host "`n✅ Verification complete. Ready to deploy!`n" -ForegroundColor Green
```

**Save as:** `verify-deployment.ps1`

**Run before EVERY push:**
```powershell
.\verify-deployment.ps1
```

---

## 📝 QUICK REFERENCE COMMANDS

```powershell
# Complete deployment sequence
git checkout development
git add -A
git commit -m "Release vX.X.X-complete - [Feature]"
git push origin development

# Display version
$version = (Get-Content config/version.php | Select-String "env\('APP_VERSION',\s*'([^']+)'").Matches.Groups[1].Value
Write-Host "`n🚀 PUSHING VERSION: v$version`n" -ForegroundColor Green

# Merge and tag
git checkout release
git merge development -m "Merge: Release vX.X.X-complete"
git tag -a vX.X.X-complete -m "Release vX.X.X-complete - [Feature]"
git push origin release --tags
git checkout development
```

---

## 🎯 SUMMARY

**Before EVERY deployment, complete this checklist:**

- [ ] Read this entire file
- [ ] Determine next version number (x.x.x-complete format)
- [ ] Update config/version.php
- [ ] Update .env.example
- [ ] Update index.html
- [ ] Update README.md
- [ ] Update RELEASE_NOTES.md (including moving old section)
- [ ] Run verification script
- [ ] Verify all files show SAME version
- [ ] Commit to development with version in message
- [ ] Push development branch
- [ ] Display version number before pushing
- [ ] Merge to release
- [ ] Create annotated git tag
- [ ] Push with --tags flag
- [ ] Return to development branch
- [ ] Verify tag exists on GitHub

**Remember:** Version consistency across ALL files is CRITICAL for proper update detection!

---

**Last Updated:** December 8, 2025  
**For VantaPress Version:** 1.1.3-complete and above
