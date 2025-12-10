<?php
/**
 * TCC School CMS - Hook Manager
 * 
 * Provides WordPress-style hooks and filters system for TCC School CMS.
 * Enables plugin/module extensibility through actions and filters.
 * 
 * @package TCC_School_CMS
 * @subpackage Services\CMS
 * @author Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * @version 1.0.0
 * @license Commercial / Paid
 * 
 * Copyright (c) 2025 Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * All Rights Reserved.
 * 
 * Contact Information:
 * Email: chardy.tsadiq02@gmail.com
 * Mobile: +63 915 0388 448
 * 
 * This software is proprietary and confidential. Unauthorized copying,
 * modification, distribution, or use of this software, via any medium,
 * is strictly prohibited without explicit written permission from the author.
 */

namespace App\Services\CMS;

class HookManager
{
    protected array $actions = [];
    protected array $filters = [];
    protected array $currentFilter = [];
    protected int $nestingLevel = 0;

    /**
     * Add an action hook
     *
     * @param string $hook
     * @param callable $callback
     * @param int $priority
     * @return void
     */
    public function addAction(string $hook, callable $callback, int $priority = 10): void
    {
        if (!isset($this->actions[$hook])) {
            $this->actions[$hook] = [];
        }

        if (!isset($this->actions[$hook][$priority])) {
            $this->actions[$hook][$priority] = [];
        }

        $this->actions[$hook][$priority][] = $callback;
    }

    /**
     * Execute an action hook
     *
     * @param string $hook
     * @param mixed ...$args
     * @return void
     */
    public function doAction(string $hook, ...$args): void
    {
        if (!isset($this->actions[$hook])) {
            return;
        }

        // Sort by priority
        ksort($this->actions[$hook]);

        foreach ($this->actions[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                try {
                    call_user_func_array($callback, $args);
                } catch (\Exception $e) {
                    \Log::error("Action hook '{$hook}' failed: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Remove an action hook
     *
     * @param string $hook
     * @param callable|null $callback
     * @param int|null $priority
     * @return bool
     */
    public function removeAction(string $hook, ?callable $callback = null, ?int $priority = null): bool
    {
        if (!isset($this->actions[$hook])) {
            return false;
        }

        // Remove all callbacks for this hook
        if ($callback === null && $priority === null) {
            unset($this->actions[$hook]);
            return true;
        }

        // Remove all callbacks with specific priority
        if ($callback === null && $priority !== null) {
            if (isset($this->actions[$hook][$priority])) {
                unset($this->actions[$hook][$priority]);
                return true;
            }
            return false;
        }

        // Remove specific callback
        if ($priority !== null) {
            if (isset($this->actions[$hook][$priority])) {
                foreach ($this->actions[$hook][$priority] as $key => $registeredCallback) {
                    if ($registeredCallback === $callback) {
                        unset($this->actions[$hook][$priority][$key]);
                        return true;
                    }
                }
            }
        } else {
            // Search through all priorities
            foreach ($this->actions[$hook] as $p => $callbacks) {
                foreach ($callbacks as $key => $registeredCallback) {
                    if ($registeredCallback === $callback) {
                        unset($this->actions[$hook][$p][$key]);
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Check if action has callbacks
     *
     * @param string $hook
     * @param callable|null $callback
     * @return bool|int
     */
    public function hasAction(string $hook, ?callable $callback = null): bool|int
    {
        if (!isset($this->actions[$hook])) {
            return false;
        }

        if ($callback === null) {
            return count($this->actions[$hook]) > 0;
        }

        foreach ($this->actions[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $registeredCallback) {
                if ($registeredCallback === $callback) {
                    return $priority;
                }
            }
        }

        return false;
    }

    /**
     * Add a filter hook
     *
     * @param string $filter
     * @param callable $callback
     * @param int $priority
     * @return void
     */
    public function addFilter(string $filter, callable $callback, int $priority = 10): void
    {
        if (!isset($this->filters[$filter])) {
            $this->filters[$filter] = [];
        }

        if (!isset($this->filters[$filter][$priority])) {
            $this->filters[$filter][$priority] = [];
        }

        $this->filters[$filter][$priority][] = $callback;
    }

    /**
     * Apply filter hooks to a value
     *
     * @param string $filter
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     */
    public function applyFilters(string $filter, mixed $value, ...$args): mixed
    {
        if (!isset($this->filters[$filter])) {
            return $value;
        }

        // Track current filter
        $this->currentFilter[] = $filter;
        $this->nestingLevel++;

        // Sort by priority
        ksort($this->filters[$filter]);

        array_unshift($args, $value);

        foreach ($this->filters[$filter] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                try {
                    $value = call_user_func_array($callback, $args);
                    $args[0] = $value;
                } catch (\Exception $e) {
                    \Log::error("Filter hook '{$filter}' failed: " . $e->getMessage());
                }
            }
        }

        array_pop($this->currentFilter);
        $this->nestingLevel--;

        return $value;
    }

    /**
     * Remove a filter hook
     *
     * @param string $filter
     * @param callable|null $callback
     * @param int|null $priority
     * @return bool
     */
    public function removeFilter(string $filter, ?callable $callback = null, ?int $priority = null): bool
    {
        if (!isset($this->filters[$filter])) {
            return false;
        }

        // Remove all callbacks for this filter
        if ($callback === null && $priority === null) {
            unset($this->filters[$filter]);
            return true;
        }

        // Remove all callbacks with specific priority
        if ($callback === null && $priority !== null) {
            if (isset($this->filters[$filter][$priority])) {
                unset($this->filters[$filter][$priority]);
                return true;
            }
            return false;
        }

        // Remove specific callback
        if ($priority !== null) {
            if (isset($this->filters[$filter][$priority])) {
                foreach ($this->filters[$filter][$priority] as $key => $registeredCallback) {
                    if ($registeredCallback === $callback) {
                        unset($this->filters[$filter][$priority][$key]);
                        return true;
                    }
                }
            }
        } else {
            // Search through all priorities
            foreach ($this->filters[$filter] as $p => $callbacks) {
                foreach ($callbacks as $key => $registeredCallback) {
                    if ($registeredCallback === $callback) {
                        unset($this->filters[$filter][$p][$key]);
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Check if filter has callbacks
     *
     * @param string $filter
     * @param callable|null $callback
     * @return bool|int
     */
    public function hasFilter(string $filter, ?callable $callback = null): bool|int
    {
        if (!isset($this->filters[$filter])) {
            return false;
        }

        if ($callback === null) {
            return count($this->filters[$filter]) > 0;
        }

        foreach ($this->filters[$filter] as $priority => $callbacks) {
            foreach ($callbacks as $registeredCallback) {
                if ($registeredCallback === $callback) {
                    return $priority;
                }
            }
        }

        return false;
    }

    /**
     * Get current filter being executed
     *
     * @return string|null
     */
    public function currentFilter(): ?string
    {
        return end($this->currentFilter) ?: null;
    }

    /**
     * Check if doing a specific filter
     *
     * @param string|null $filter
     * @return bool
     */
    public function doingFilter(?string $filter = null): bool
    {
        if ($filter === null) {
            return $this->nestingLevel > 0;
        }

        return in_array($filter, $this->currentFilter);
    }

    /**
     * Remove all hooks (actions and filters) for a specific tag
     *
     * @param string $tag
     * @return bool
     */
    public function removeAllHooks(string $tag): bool
    {
        $removed = false;

        if (isset($this->actions[$tag])) {
            unset($this->actions[$tag]);
            $removed = true;
        }

        if (isset($this->filters[$tag])) {
            unset($this->filters[$tag]);
            $removed = true;
        }

        return $removed;
    }

    /**
     * Get all registered actions
     *
     * @param string|null $hook
     * @return array
     */
    public function getActions(?string $hook = null): array
    {
        if ($hook !== null) {
            return $this->actions[$hook] ?? [];
        }

        return $this->actions;
    }

    /**
     * Get all registered filters
     *
     * @param string|null $filter
     * @return array
     */
    public function getFilters(?string $filter = null): array
    {
        if ($filter !== null) {
            return $this->filters[$filter] ?? [];
        }

        return $this->filters;
    }

    /**
     * Count total number of callbacks for a hook
     *
     * @param string $hook
     * @param bool $isFilter
     * @return int
     */
    public function countCallbacks(string $hook, bool $isFilter = false): int
    {
        $hooks = $isFilter ? $this->filters : $this->actions;

        if (!isset($hooks[$hook])) {
            return 0;
        }

        $count = 0;
        foreach ($hooks[$hook] as $callbacks) {
            $count += count($callbacks);
        }

        return $count;
    }

    /**
     * Clear all hooks
     *
     * @return void
     */
    public function clearAllHooks(): void
    {
        $this->actions = [];
        $this->filters = [];
        $this->currentFilter = [];
        $this->nestingLevel = 0;
    }

    /**
     * Execute action and return timing information (for debugging)
     *
     * @param string $hook
     * @param mixed ...$args
     * @return array
     */
    public function doActionTimed(string $hook, ...$args): array
    {
        $start = microtime(true);
        $this->doAction($hook, ...$args);
        $end = microtime(true);

        return [
            'hook' => $hook,
            'execution_time' => $end - $start,
            'callback_count' => $this->countCallbacks($hook, false),
        ];
    }

    /**
     * Apply filter and return timing information (for debugging)
     *
     * @param string $filter
     * @param mixed $value
     * @param mixed ...$args
     * @return array
     */
    public function applyFiltersTimed(string $filter, mixed $value, ...$args): array
    {
        $start = microtime(true);
        $result = $this->applyFilters($filter, $value, ...$args);
        $end = microtime(true);

        return [
            'filter' => $filter,
            'result' => $result,
            'execution_time' => $end - $start,
            'callback_count' => $this->countCallbacks($filter, true),
        ];
    }
}
