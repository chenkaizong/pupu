<?php

namespace App\Models\Diy;

use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class DiyPermissionsModel extends Permission
{


    use ModelTree {
        ModelTree::boot as treeBoot;
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTitleColumn('name');

    }


    /**
     * Get options for Select field in form.
     *
     * @param \Closure|null $closure
     * @param string        $rootText
     *
     * @return array
     */
    public static function selectRoleOptions(\Closure $closure = null, $rootText = 'ROOT')
    {
        $options = (new static())->withQuery($closure)->buildSelectRoleOptions();

//        dump(collect($options)->pluck('title','slug'));die;
        return collect($options)->pluck('title','slug');
    }

    /**
     * Build options of select field in form.
     *
     * @param array  $nodes
     * @param int    $parentId
     * @param string $prefix
     * @param string $space
     *
     * @return array
     */
    protected function buildSelectRoleOptions(array $nodes = [], $parentId = 0, $prefix = '', $space = '&nbsp;')
    {
        $prefix = $prefix ?: '┝'.$space;

        $options = [];

        if (empty($nodes)) {
            $nodes = $this->allNodes();
        }

        foreach ($nodes as $index => $node) {
            if ($node[$this->parentColumn] == $parentId) {
                $node[$this->titleColumn] = $prefix.$space.$node[$this->titleColumn];

                $childrenPrefix = str_replace('┝', str_repeat($space, 6), $prefix).'┝'.str_replace(['┝', $space], '', $prefix);

                $children = $this->buildSelectRoleOptions($nodes, $node[$this->getKeyName()], $childrenPrefix);

                $options[$node[$this->getKeyName()]] = ['title'=>$node[$this->titleColumn],'slug'=>$node['slug']] ;

                if ($children) {
                    $options += $children;
                }
            }
        }

        return $options;
    }



}
