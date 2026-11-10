<?php

namespace ErnestDefoe\TierLists\Gdpr;

use ErnestDefoe\TierLists\TierList;
use Flarum\Gdpr\Data\Type;

/** A member's lists in a data export. Erasure deletes them; anonymizing keeps them without a name. Registered only when flarum/gdpr is enabled. */
class TierListData extends Type
{
    public static function exportDescription(): string
    {
        return self::staticTranslator()->trans('ernestdefoe-tier-lists.lib.gdpr.export');
    }

    public static function anonymizeDescription(): string
    {
        return self::staticTranslator()->trans('ernestdefoe-tier-lists.lib.gdpr.anonymize');
    }

    public static function deleteDescription(): string
    {
        return self::staticTranslator()->trans('ernestdefoe-tier-lists.lib.gdpr.delete');
    }

    public function export(): ?array
    {
        $export = [];
        foreach (TierList::query()->with('items')->where('user_id', $this->user->id)->get() as $list) {
            $export[] = ["tier-lists/list-{$list->id}.json" => $this->encodeForExport([
                'title' => $list->title,
                'description' => $list->description,
                'tiers' => $list->tierRows(),
                'items' => $list->items->map(fn ($item) => $item->only(['label', 'image', 'tier']))->all(),
                'remix_of' => $list->parent_id,
                'created_at' => $list->created_at,
                'updated_at' => $list->updated_at,
            ])];
        }

        return $export;
    }

    public function anonymize(): void
    {
        TierList::query()->where('user_id', $this->user->id)->update(['user_id' => null]);
        $this->db()->table('tier_list_likes')->where('user_id', $this->user->id)->delete();
    }

    public function delete(): void
    {
        TierList::query()->where('user_id', $this->user->id)->delete();
        $this->db()->table('tier_list_likes')->where('user_id', $this->user->id)->delete();
    }

    private function db(): \Illuminate\Database\ConnectionInterface
    {
        return resolve(\Illuminate\Database\ConnectionInterface::class);
    }
}
