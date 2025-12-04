# 📦 Release Directory

This directory is used for creating release packages before publishing to GitHub.

## Purpose

When creating a new release, this folder temporarily holds:
- Pre-packaged zip files
- Build artifacts
- Release candidate files
- Deployment-ready packages

## Usage

**Automated releases** are created using the PowerShell script:
```powershell
.\create-release-package.ps1
```

**Manual process:**
1. Run build script (if needed)
2. Copy distribution files here
3. Create zip package
4. Upload to GitHub Releases
5. Clean up this directory

## ⚠️ Important

- This folder should be **empty in version control**
- Files here are **temporary** and **not committed**
- Always clean up after release creation
- Actual releases are on GitHub: https://github.com/sepiroth-x/vantapress/releases

## Current Release

**Latest:** v1.0.7-complete  
**Download:** https://github.com/sepiroth-x/vantapress/releases/tag/v1.0.7-complete

---

**Note:** If you're looking for release downloads, visit the GitHub Releases page, not this directory.
