<template>
  <div class="channel-managemnet">
    <h2>渠道管理</h2>
    <el-card class="box-card">
      <float-button @click.native="addChannel"/>
      <el-table style="width: 100%" border :data="channels">
        <el-table-column prop="id" label="ID" width="180"></el-table-column>
        <el-table-column prop="name" label="渠道名称"></el-table-column>
        <el-table-column label="操作">
          <template scope="scope">
            <el-button size="small" icon="edit"
                       @click="editChannelName(scope.$index, scope.row)">
            </el-button>
            <el-button size="small" type="danger" icon="delete"
                       @click="removeChannel(scope.$index, scope.row)">
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </div>
</template>

<script>
  import FloatButton from '~components/FloatButton.vue'
  import axios from '../../../config/axios'
  import qs from 'qs'

  export default {
    name: 'channelManagement',
    components: {
      FloatButton
    },
    data () {
      return {
        channels: this.$store.state.channels ? this.$store.state.channels : []
      }
    },
    watch: {
      channels (channels) {
        this.$store.commit('getChannels', channels)
      }
    },
    methods: {
      addChannel () {
        let self = this
        this.$prompt('渠道名称', {
          showCancelButton: false,
          confirmButtonText: '创建'
        })
          .then(({value}) => {
            axios.post('/management/channels', qs.stringify({name: value}))
              .then(res => {
                self.channels.push(res.channel)
              })
          })
      },
      editChannelName (index, row) {
        let self = this
        this.$prompt('渠道名称', {
          showCancelButton: false,
          confirmButtonText: '更改',
          inputValue: row.name
        })
          .then(({value}) => {
            axios.patch(`/management/channels/${row.id}`, qs.stringify({name: value}))
              .then(res => {
                self.channels.splice(index, 1, res.channel)
              })
          })
      },
      toggleChannelState (index, row) {
        axios.patch(`/management/channels/${row.id}`, qs.stringify({
          state: row.state ? 0 : 1
        }))
          .then(res => {
          })
      },
      removeChannel (index, row) {
        let self = this
        axios.delete(`/management/channels/${row.id}`)
          .then(res => {
            self.channels.splice(index, 1)
          })
      }
    },
    mounted () {
      let self = this
      !(async function () {
        if (!self.$store.state.channels) {
          let {channels} = await axios.get('/management/channels')
          self.channels = channels
        }
        self.$store.state.loading = false
      }())
    }
  }
</script>

<style scoped lang="scss"></style>
