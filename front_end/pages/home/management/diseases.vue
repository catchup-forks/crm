<template>
  <div class="disease-management">
    <h2>病种管理</h2>
    <el-card class="box-card">
      <el-tree
        ref="treeParent"
        :data="diseases"
        :props="defaultProps"
        highlight-current
        node-key="id"
        default-expand-all
        :render-content="renderContent">
      </el-tree>
    </el-card>
  </div>
</template>

<script>
  import axios from '../../../config/axios'
  import qs from 'qs'
  export default {
    data () {
      return {
        diseases: [{id: 1, name: 'root', children: []}],
        defaultProps: {
          children: 'children',
          label: 'name'
        }
      }
    },
    methods: {
      appendDisease (store, data) {
        this.$prompt('病种名称', {
          showCancelButton: false,
          confirmButtonText: '创建'
        })
          .then(({value}) => {
            axios.get(`/management/diseases/${data.id}/${value}`)
              .then(res => {
                store.append({id: res.disease.id, name: res.disease.name, children: []}, data)
              })
          })
      },
      removeDsiease (store, data) {
        axios.delete(`/management/diseases/${data.id}`)
          .then(res => {
            store.remove(data)
          })
      },
      editDsiease (store, data) {
        this.$prompt('病种名称', {
          showCancelButton: false,
          confirmButtonText: '更改'
        })
          .then(({value}) => {
            axios.patch(`/management/diseases/${data.id}`, qs.stringify({name: value}))
              .then(res => {
                data.name = res.disease.name
              })
          })
      },
      renderContent (h, {node, data, store}) {
        return (
          <span style="width:100%;display:inline; overflow:hidden">
          <span>{node.label}</span>
        <span style="float: right">
          <el-button size="mini" type="primary" icon="plus" on-click={ () => this.appendDisease(store, data) }></el-button>
        <el-button size="mini" icon="edit" on-click={ () => this.editDsiease(store, data) }></el-button>
        <el-button size="mini" type="danger" icon="delete" on-click={ () => this.removeDsiease(store, data) }></el-button>
        </span>
        </span>)
      }
    },
    mounted () {
      let self = this
      axios.get('/management/diseases')
        .then(res => {
          for (let disease in res.diseases) {
            self.diseases[0].children.push(res.diseases[disease])
            self.$store.state.loading = false
          }
        })
    }
  }
</script>

<style scoped lang="scss">
  .disease-management{
    .el-tree{
      border: none
    }
  }
</style>
