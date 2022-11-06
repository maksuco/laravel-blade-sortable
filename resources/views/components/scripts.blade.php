<script>
    const laravelBladeSortable = () => {
        return {
            name: '',
            sortOrder: [],
            animation: 200,
            ghostClass: '',
            dragHandle: null,
            group: null,
            allowSort: true,
            allowDrop: true,
            pull: true,
            swap: false,

            wireComponent: null,
            wireOnSortOrderChange: null,

            start() {
                this.sortOrder = this.computeSortOrderFromChildren()
                var swap = (this.swap != false)? true : false;
                console.log(swap,this.swap);

                window.Sortable.create(this.$refs.root, {
                    handle: this.dragHandle,
                    animation: this.animation,
                    ghostClass: this.ghostClass,
                    delay: 100,
                    group: {
                        name: this.group,
                        put: this.allowDrop,
                        pull: this.pull
                    },
                    sort: this.allowSort,
                    filter: '.noDragging',
                    forceFallback: true,
                    swap: swap,
                    swapClass: this.swap,
                    onSort: evt => {

                        const previousSortOrder = [...this.sortOrder]
                        this.sortOrder = this.computeSortOrderFromChildren()
                        if (!this.$wire) {
                            return;
                        }

                        const from = evt?.from?.dataset?.name
                        const to = evt?.to?.dataset?.name
                        const oldIndex = evt?.oldIndex
                        const newIndex = evt?.newIndex
                        const itemId = evt.item.id ?? null

                        this.$wire.call(
                            this.wireOnSortOrderChange,
                            this.sortOrder,
                            previousSortOrder,
                            this.name,
                            from,
                            to,
                            oldIndex,
                            newIndex,
                            itemId
                        )
                    },
                });
            },

            computeSortOrderFromChildren() {
                return [].slice.call(this.$refs.root.children)
                    .map(child => child.dataset.sortKey)
                    .filter(sortKey => sortKey)
            }
        }
    }
</script>
