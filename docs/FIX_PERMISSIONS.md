# CRITICAL FIX NEEDED

## Problem Identified:
**Root directory permissions: 0750**

This means:
- Owner: read+write+execute (7)
- Group: read+execute (5)
- Others: **NO ACCESS** (0)

Apache runs as "others" and is **BLOCKED** from accessing the directory!

## Solution:

### Via iFastNet File Manager:
1. Right-click on root folder (htdocs or public_html)
2. Select "Change Permissions" or "CHMOD"
3. Set to **755** (or 0755)
   - Check: Read, Write, Execute for Owner
   - Check: Read, Execute for Group  
   - Check: Read, Execute for Public/World
4. Apply to directory (not recursively)

### Via Command (if you have SSH/terminal):
```bash
chmod 755 /home/hawkeye1/dev2.thevillainousacademy.it.nf
```

## After fixing:
- Root directory will be **0755** (readable by Apache)
- `/admin` will load correctly
- FilamentPHP will work with full styling

## This is why you get 403:
Apache tries to access `/admin` → Root `.htaccess` tries to rewrite → Apache can't read root directory (0750) → **403 Forbidden**

Fix the permissions and `/admin` will instantly work!
