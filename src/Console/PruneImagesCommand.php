<?php

namespace ErnestDefoe\TierLists\Console;

use ErnestDefoe\TierLists\Images;
use Flarum\Console\AbstractCommand;

/** Deletes uploads no saved list ever claimed (an editor closed without saving). Runs daily. */
class PruneImagesCommand extends AbstractCommand
{
    public function __construct(private readonly Images $images)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('tier-lists:prune-images')->setDescription('Delete tier list pictures no list uses');
    }

    protected function fire(): int
    {
        $this->info($this->images->pruneAbandoned().' deleted');

        return 0;
    }
}
