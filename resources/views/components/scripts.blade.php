<script>
    const laravelBladeSortable = () => {
        return {
            parent: false,
            name: '',
            sortOrder: [],
            animation: 500,
            ghostClass: '',
            dragHandle: null,
            group: null,
            allowSort: true,
            allowDrop: true,
            pull: true,
            swap: false,

            wireComponent: null,
            wireOnSortOrderChange: null,
            duplicate_call: false,

            start() {
                console.log('initial',this.parent);
                this.parent = (this.parent)? document.getElementById(this.parent) : null;
                this.sortOrder = this.computeSortOrderFromChildren()
                var swap = (this.swap != false)? true : false;

                window.Sortable.create(this.$refs.root, {
                    handle: this.dragHandle,
                    animation: this.animation,
                    ghostClass: this.ghostClass,
                    delay: 50,
                    group: {
                        name: this.group,
                        put: this.allowDrop,
                        pull: this.pull
                    },
                    sort: this.allowSort,
                    filter: '.noDragging',
                    //forceFallback: true,
		            fallbackOnBody: true,
		            swapThreshold: 0.65,
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
                        const toId = evt?.to?.id

                        this.$wire.call(
                            this.wireOnSortOrderChange,
                            this.sortOrder,
                            previousSortOrder,
                            this.name,
                            from,
                            to,
                            oldIndex,
                            newIndex,
                            itemId,
                            toId
                        )
                    },
                    onStart: function (evt) {
                        console.log(evt);
                        console.log(this.parent);
                        if(this.parent) this.parent.classList.add("sortable-parent");
                    },
                    onEnd: function () {
                        if(this.parent) this.parent.classList.remove("sortable-parent");
                    }
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
