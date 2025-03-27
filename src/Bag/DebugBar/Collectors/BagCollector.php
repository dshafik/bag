<?php

declare(strict_types=1);

namespace Bag\DebugBar\Collectors;

use Bag\Bag;
use DebugBar\DataCollector\DataCollectorInterface;
use DebugBar\DataCollector\MessagesCollector;

if (!class_exists(MessagesCollector::class)) {
    class BagCollector
    {
        public static function add(Bag $bag): void
        {
            // Do nothing
        }

        public static function init(): void
        {
            // Do nothing
        }
    }
} else {
    class BagCollector extends MessagesCollector implements DataCollectorInterface
    {
        /**
         * @var array<string, array{bag: Bag, location: array{file: string|null, line: int|null}|false, type: string}>
         */
        protected static array $bags = [];

        public static function add(Bag $bag): void
        {
            $location = false;
            $trace = collect(\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS))
                ->firstWhere(fn ($v) => isset($v['file']) &&
                    !str_contains($v['file'], '/vendor/') &&
                    !\str_starts_with($v['function'], '__'));

            $type = 'unknown';
            if ($trace !== null) {
                $location = ['file' => $trace['file'] ?? null, 'line' => $trace['line'] ?? null];
                $type = $trace['function'];
            }

            self::$bags[$bag::class . '#' . \spl_object_id($bag)] = ['bag' => $bag, 'location' => $location, 'type' => $type];
        }

        /**
         * @return array{messages: array<mixed>, count: int}
         */
        public function collect(): array
        {
            $localPath = config('debugbar.local_sites_path') ?: base_path();
            $remoteSitesPath = config('debugbar.remote_sites_path');
            $remotePaths = array_filter(explode(',', is_string($remoteSitesPath) ? $remoteSitesPath : '')) ?: [base_path()];

            $this->setXdebugReplacements(array_fill_keys($remotePaths, $localPath));
            $editor = \config('debugbar.editor');
            if (is_string($editor)) {
                $this->setEditorLinkTemplate($editor);
            }

            /**
             * @var array{bag: Bag, location: array{file: string, line: int|null}, type: string} $message
             */
            foreach (self::$bags as $message) {
                $this->addMessage($message['bag'], $message['type']);
                $xdebugLink = $this->getXdebugLink($message['location']['file'], $message['location']['line'] ?? null);
                $this->messages[\array_key_last($this->messages)]['xdebug_link'] = $xdebugLink;
            }

            return parent::collect();
        }

        public function getName(): string
        {
            return 'bags';
        }

        public static function init(): void
        {
            self::$bags = [];
        }
    }
}
