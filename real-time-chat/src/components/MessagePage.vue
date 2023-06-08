<template>
    <el-main>
        <el-card style="width: 800px;">
            <el-form :model="neuralDeskMessage" label-width="120px" label-position="top">
                <el-form-item label="userId">
                    <el-select v-model="neuralDeskMessage.userId" class="m-2" placeholder="Select" size="large">
                        <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value" />
                    </el-select>
                </el-form-item>
                <el-form-item label="Message">
                    <el-input v-model="neuralDeskMessage.message" type="textarea" />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="onSubmit">Send</el-button>
                    <el-button>Cancel</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <!-- <el-card style="margin-top: 50px;">
            <el-table :data="tableData" style="width: 100%; height: 500px;">
                <el-table-column prop="userId" label="Id del tecnico" width="180" />
                <el-table-column prop="message" label="Mensaje" />
            </el-table>
        </el-card> -->

        <div class="container" ref="container">

            <div :class="[message.userId == 1 ? 'message myMessage' : 'message yourMessage']" v-for="message in messageLog">
                <p>{{ message.message }}</p>
            </div>

            <!-- <div class="message myMessage">
                <p>Hola esto es un mensaje</p>
            </div>
            <div class="message yourMessage">
                <p>Si tonto?</p>
            </div> -->
        </div>
    </el-main>
</template>

<style>
.container {
    border: 1px solid lightgray;
    height: 300px;
    width: 800px;
    margin-top: 10px;
    overflow-y: scroll;
    justify-content: revert;
    flex-direction: column;
    display: flex;
}

.message {
    display: flex;
}

.message.myMessage p {
    background-color: rgb(43, 158, 211);
    color: white;
    padding: 10px;
    border-radius: 5px;
}

.myMessage {
    justify-content: flex-end;
    margin-right: 10px;
}

.message.yourMessage p {
    background-color: rgb(148, 148, 148);
    color: white;
    padding: 10px;
    border-radius: 5px;
}

.yourMessage {
    margin-left: 10px;
}
</style>
  
<script setup>
import { reactive, ref } from 'vue'
import axios from "axios"


const container = ref('')
// import { io } from 'socket.io-client';
// import { Server } from "socket.io";

const messageLog = ref([])

var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function (e) {
    console.log("Connection established!");
};

conn.onmessage = function (e) {
    console.log(e.data);
    console.log('Mira acabas de mandar un mensaje');
    fetchMessages()

};

const neuralDeskMessage = reactive({
    userId: '',
    message: '',
})

async function onSubmit() {
    const formData = new FormData()
    formData.append('userId', neuralDeskMessage.userId)
    formData.append('message', neuralDeskMessage.message)
    await axios.post('http://localhost/api/', formData)
    console.log('submit!')
    neuralDeskMessage.userId = ''
    neuralDeskMessage.message = ''
    await fetchMessages()
    conn.send(formData)
    container.value.scrollTop = container.value.scrollHeight;
}

const options = [{
    value: '1',
    label: 'Fernando',
},
{
    value: '2',
    label: 'Rodrigo',
},

]



async function fetchMessages() {

    const response = await axios.get('http://localhost/api/', {
        method: 'HEAD',
        mode: 'no-cors',

    })
    await goBottom()
    const data = response.data
    messageLog.value = data
    console.log(data)
    return data
}

fetchMessages()

async function goBottom() {
    container.value.scrollTop = container.value.scrollHeight;
}



</script>