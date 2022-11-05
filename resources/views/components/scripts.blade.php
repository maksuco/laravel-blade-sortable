<script>
    const laravelBladeSortable = () => {
        return {
            name: '',
            sortOrder: [],
            animation: 150,
            ghostClass: '',
            dragHandle: null,
            group: null,
            allowSort: true,
            allowDrop: true,
            pull: true,

            wireComponent: null,
            wireOnSortOrderChange: null,

            init() {
                this.sortOrder = this.computeSortOrderFromChildren()

                // var pull = this.clone ? "'clone'" : true;
                // var revertClone = this.clone ? true : false;
                console.log(this.pull, this.group,this.allowDrop,this.allowSort);

                window.Sortable.create(this.$refs.root, {
                    handle: this.dragHandle,
                    animation: this.animation,
                    ghostClass: this.ghostClass,
                    group: {
                        name: this.group,
                        put: this.allowDrop,
                        pull: this.pull
                    },
                    sort: this.allowSort,
                    filter: '.noDragging',
                    forceFallback: true,
                    onSort: evt => {
                        console.log(evt);
                        const previousSortOrder = [...this.sortOrder]
                        this.sortOrder = this.computeSortOrderFromChildren()
                        if (!this.$wire) {
                            return;
                        }

                        const from = evt?.from?.dataset?.name
                        const to = evt?.to?.dataset?.name
                        const oldIndex = evt?.oldIndex
                        const newIndex = evt?.newIndex

                        this.$wire.call(
                            this.wireOnSortOrderChange,
                            this.sortOrder,
                            previousSortOrder,
                            this.name,
                            from,
                            to,
                            oldIndex,
                            newIndex,
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
