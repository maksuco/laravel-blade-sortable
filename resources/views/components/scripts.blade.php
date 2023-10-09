<script>
    const laravelBladeSortable = () => {
        return {
            name: '',
            sortOrder: [],
            animation: 500,
            ghostClass: '',
            dragHandle: null,
            group: null,
            allowSort: true,
            allowDrop: true,
            allowPull: true,
            swap: false,

            wireComponent: null,
            wireOnSortOrderChange: null,
            duplicate_call: false,

            start() {
                var parent = document.getElementById('sortableParent');
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
                        pull: this.allowPull
                    },
                    sort: this.allowSort,
                    filter: '.noDragging',
                    //forceFallback: true,
		            fallbackOnBody: false,
		            swapThreshold: 0.65,
                    swap: swap,
                    swapClass: this.swap,
                    onChoose: function (evt) {
                        //console.log('onChoose',evt,evt.item,evt.item.id,evt.parent);
                        if (evt.item.id.includes("g_")) {
                            const sortableElements = document.querySelectorAll('.sortable-group-inside');
                            sortableElements.forEach((element) => {
                                Sortable.get(element).option('group', { put: false });
                            });
                        }
                    },
                    onSort: evt => {
                        const itemId = evt.item.id ?? null
                        const toId = evt?.to?.id
                        console.log('onSort',evt,itemId,toId);
                        // if(itemId.includes("group_") && toId.includes("group_")){
                        //     evt.preventDefault();
                        //     return false;
                        // }

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
                            itemId,
                            toId
                        )
                    },
                    onStart: function () {
                        if(parent) {parent.classList.add("sortable-parent")};
                    },
                    onEnd: function () {
                        if(parent) {parent.classList.remove("sortable-parent")};
                        const sortableElements = document.querySelectorAll('.sortable-group-inside');
                        sortableElements.forEach((element) => {
                            Sortable.get(element).option('group', { put: true });
                        });
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
