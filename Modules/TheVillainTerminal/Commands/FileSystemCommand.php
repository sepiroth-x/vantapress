<?php

namespace Modules\TheVillainTerminal\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

/**
 * Unix-like file system navigation commands (simulated)
 * 
 * Provides: pwd, cd, ls, mkdir, rmdir, touch, rm, cat
 */
class FileSystemCommand
{
    /**
     * Current working directory (simulated)
     */
    protected function getCurrentDir(): string
    {
        return Session::get('villain_terminal_cwd', '/home/villain');
    }

    /**
     * Set current working directory
     */
    protected function setCurrentDir(string $path): void
    {
        Session::put('villain_terminal_cwd', $path);
    }

    /**
     * Resolve path (handle . and .. and relative paths)
     */
    protected function resolvePath(string $path): string
    {
        $cwd = $this->getCurrentDir();
        
        // Absolute path
        if (str_starts_with($path, '/')) {
            $fullPath = $path;
        } else {
            // Relative path
            $fullPath = $cwd . '/' . $path;
        }

        // Normalize path (handle .. and .)
        $parts = explode('/', $fullPath);
        $resolved = [];

        foreach ($parts as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }
            if ($part === '..') {
                array_pop($resolved);
            } else {
                $resolved[] = $part;
            }
        }

        return '/' . implode('/', $resolved);
    }

    /**
     * Get simulated directory structure
     */
    protected function getVirtualFS(): array
    {
        return [
            '/home' => ['type' => 'dir', 'children' => ['villain']],
            '/home/villain' => ['type' => 'dir', 'children' => ['projects', 'documents', 'downloads']],
            '/home/villain/projects' => ['type' => 'dir', 'children' => ['vantapress', 'personal']],
            '/home/villain/projects/vantapress' => ['type' => 'dir', 'children' => ['themes', 'modules', 'README.md']],
            '/home/villain/projects/vantapress/README.md' => ['type' => 'file', 'content' => 'VantaPress - WordPress Philosophy, Laravel Power'],
            '/home/villain/projects/vantapress/themes' => ['type' => 'dir', 'children' => ['BasicTheme', 'TheVillainArise']],
            '/home/villain/projects/vantapress/modules' => ['type' => 'dir', 'children' => ['VPEssential1', 'TheVillainTerminal']],
            '/home/villain/projects/personal' => ['type' => 'dir', 'children' => []],
            '/home/villain/documents' => ['type' => 'dir', 'children' => ['notes.txt', 'todo.txt']],
            '/home/villain/documents/notes.txt' => ['type' => 'file', 'content' => 'Remember to push to production!'],
            '/home/villain/documents/todo.txt' => ['type' => 'file', 'content' => '1. Fix bugs\n2. Write docs\n3. Deploy'],
            '/home/villain/downloads' => ['type' => 'dir', 'children' => []],
        ];
    }

    /**
     * pwd - Print working directory
     */
    public function pwd(array $args): array
    {
        return [
            'output' => $this->getCurrentDir(),
            'success' => true
        ];
    }

    /**
     * cd - Change directory
     */
    public function cd(array $args): array
    {
        if (empty($args)) {
            // cd with no args goes to home
            $this->setCurrentDir('/home/villain');
            return ['output' => '', 'success' => true];
        }

        $target = $args[0];
        $newPath = $this->resolvePath($target);
        $fs = $this->getVirtualFS();

        // Check if directory exists
        if (!isset($fs[$newPath])) {
            return [
                'output' => "<span style='color: #ff0000;'>cd: {$target}: No such file or directory</span>",
                'success' => false
            ];
        }

        if ($fs[$newPath]['type'] !== 'dir') {
            return [
                'output' => "<span style='color: #ff0000;'>cd: {$target}: Not a directory</span>",
                'success' => false
            ];
        }

        $this->setCurrentDir($newPath);
        return ['output' => '', 'success' => true];
    }

    /**
     * ls - List directory contents
     */
    public function ls(array $args): array
    {
        $showHidden = in_array('-a', $args) || in_array('--all', $args);
        $longFormat = in_array('-l', $args) || in_array('--long', $args);
        
        $path = $this->getCurrentDir();
        
        // If path argument provided
        foreach ($args as $arg) {
            if (!str_starts_with($arg, '-')) {
                $path = $this->resolvePath($arg);
                break;
            }
        }

        $fs = $this->getVirtualFS();

        if (!isset($fs[$path])) {
            return [
                'output' => "<span style='color: #ff0000;'>ls: cannot access '{$path}': No such file or directory</span>",
                'success' => false
            ];
        }

        if ($fs[$path]['type'] === 'file') {
            return ['output' => basename($path), 'success' => true];
        }

        $children = $fs[$path]['children'] ?? [];
        
        if (empty($children)) {
            return ['output' => '', 'success' => true];
        }

        $output = [];
        
        if ($longFormat) {
            foreach ($children as $child) {
                $childPath = $path . '/' . $child;
                $isDir = isset($fs[$childPath]) && $fs[$childPath]['type'] === 'dir';
                $perms = $isDir ? 'drwxr-xr-x' : '-rw-r--r--';
                $color = $isDir ? '#00bfff' : '#ffffff';
                $output[] = "<span style='color: #00ff00;'>{$perms}</span> 1 villain villain 4096 Dec 10 12:00 <span style='color: {$color};'>{$child}</span>";
            }
        } else {
            $formatted = [];
            foreach ($children as $child) {
                $childPath = $path . '/' . $child;
                $isDir = isset($fs[$childPath]) && $fs[$childPath]['type'] === 'dir';
                $color = $isDir ? '#00bfff' : '#ffffff';
                $formatted[] = "<span style='color: {$color};'>{$child}</span>";
            }
            $output[] = implode('  ', $formatted);
        }

        return ['output' => implode("\n", $output), 'success' => true];
    }

    /**
     * mkdir - Create directory
     */
    public function mkdir(array $args): array
    {
        if (empty($args)) {
            return [
                'output' => "<span style='color: #ff0000;'>mkdir: missing operand</span>\nTry 'mkdir DIRECTORY'",
                'success' => false
            ];
        }

        $output = [];
        foreach ($args as $dirName) {
            if (str_starts_with($dirName, '-')) continue;
            
            $output[] = "<span style='color: #00ff00;'>✓ Created directory: {$dirName}</span>";
            $output[] = "<span style='color: #ffff00;'>(Simulated - no actual directory created)</span>";
        }

        return ['output' => implode("\n", $output), 'success' => true];
    }

    /**
     * rmdir - Remove directory
     */
    public function rmdir(array $args): array
    {
        if (empty($args)) {
            return [
                'output' => "<span style='color: #ff0000;'>rmdir: missing operand</span>\nTry 'rmdir DIRECTORY'",
                'success' => false
            ];
        }

        $output = [];
        foreach ($args as $dirName) {
            if (str_starts_with($dirName, '-')) continue;
            
            $output[] = "<span style='color: #00ff00;'>✓ Removed directory: {$dirName}</span>";
            $output[] = "<span style='color: #ffff00;'>(Simulated - no actual directory removed)</span>";
        }

        return ['output' => implode("\n", $output), 'success' => true];
    }

    /**
     * touch - Create empty file
     */
    public function touch(array $args): array
    {
        if (empty($args)) {
            return [
                'output' => "<span style='color: #ff0000;'>touch: missing file operand</span>\nTry 'touch FILE'",
                'success' => false
            ];
        }

        $output = [];
        foreach ($args as $fileName) {
            if (str_starts_with($fileName, '-')) continue;
            
            $output[] = "<span style='color: #00ff00;'>✓ Created file: {$fileName}</span>";
            $output[] = "<span style='color: #ffff00;'>(Simulated - no actual file created)</span>";
        }

        return ['output' => implode("\n", $output), 'success' => true];
    }

    /**
     * rm - Remove files
     */
    public function rm(array $args): array
    {
        if (empty($args)) {
            return [
                'output' => "<span style='color: #ff0000;'>rm: missing operand</span>\nTry 'rm FILE'",
                'success' => false
            ];
        }

        $output = [];
        foreach ($args as $fileName) {
            if (str_starts_with($fileName, '-')) continue;
            
            $output[] = "<span style='color: #00ff00;'>✓ Removed file: {$fileName}</span>";
            $output[] = "<span style='color: #ffff00;'>(Simulated - no actual file removed)</span>";
        }

        return ['output' => implode("\n", $output), 'success' => true];
    }

    /**
     * cat - Display file contents
     */
    public function cat(array $args): array
    {
        if (empty($args)) {
            return [
                'output' => "<span style='color: #ff0000;'>cat: missing file operand</span>\nTry 'cat FILE'",
                'success' => false
            ];
        }

        $fileName = $args[0];
        $path = $this->resolvePath($fileName);
        $fs = $this->getVirtualFS();

        if (!isset($fs[$path])) {
            return [
                'output' => "<span style='color: #ff0000;'>cat: {$fileName}: No such file or directory</span>",
                'success' => false
            ];
        }

        if ($fs[$path]['type'] === 'dir') {
            return [
                'output' => "<span style='color: #ff0000;'>cat: {$fileName}: Is a directory</span>",
                'success' => false
            ];
        }

        $content = $fs[$path]['content'] ?? '';
        return ['output' => $content, 'success' => true];
    }
}
