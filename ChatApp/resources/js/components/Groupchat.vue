<template>
<div class="grid h-screen col p-0 m-0 grid-rows-none grid-cols-12">
    <div class=" text-white header-1 col-span-12  bg-gray-600 p-3">
        <h3 class=" text-2xl" v-text="project.name"></h3>
        <div>{{users}}</div>
        <span v-if="activePeer" v-text="activePeer.name + ' is typing... '"></span>
    </div>
    <div id="div-chat" class=" grid col-span-12 header overflow-y-auto">
     <div class=" ">
     
        <ul  class="list-group">
            <div v-for="(task, i) in project.tasks"   :key="i" >
                <div v-if="checkdate(task)">
                    <li class=" text-center m-3 border-b-2 border-gray-300" v-text="Last_date"></li>
                </div>

                <div class="   w-2/5  relative  my-1" v-if="task.user_id != name1">
                    <div class=" w-full inline-block break-all chat-left">
                        <li class="mt-2 text-lg" v-text="task.body" ></li>
                        <li class=" text-right text-xs absolute bottom-1 right-5">{{gettime(task)}}</li>
                        <li class=" text-right text-sm absolute top-0 text-gray-300 ">{{task.user_id}}</li>
                    </div>
                    <div class="triangle-left"></div>
                </div>

                <div v-else class="flex relative  justify-end my-1">
                     <div class="triangle-right"></div>
                    <div class="chat-right inline-block break-all w-2/5">
                        <li class=" text-lg" v-text="task.body" ></li>
                        <li class=" text-right text-xs absolute bottom-1 right-7">{{gettime(task)}}</li>
                   

                    </div>
                   

                   
                </div>
            
            </div>
            
        </ul>
        </div>
       
    </div>
    <div class=" col-start-1 col-span-12 row-start-3 d-flex align-items-end m-3  flex-row ">
    <input class=" w-full h-10 rounded-xl border-2 border-gray-600 "  maxlength="300" type="text" v-model="newTask" @keyup.enter='save' @keydown="tagPeers" ></div>
  
</div>
  
</template>

<script>

    export default {
        props: ['data-project'],
        
        data() {
            return {

                project: this.dataProject,
                newTask: '',
                activePeer: false,
                typingTimer: false,
                participants: [],
                name1: window.App.user.name,
                user_id: window.App.user.id,
                users: window.users,
                is_current: false,
                Last_date: 'test',
                
            }
        },
        
        created() {
            
            window.Echo.join("tasks." + this.project.id)
            .here(users => {
                
               this.participants = users;

             
            })
            .joining(user => {
                this.participants.push(user)
            })
            .leaving(user => {
                this.participants.splice(this.participants.indexOf(user), 1)
            })
            .listen(
                "TaskCreated",
                ({ task }) => this.addTask(task)

            )
            .listenForWhisper('typing', e => {
                this.activePeer = e;

            if (this.typingTimer) clearTimeout(this.typingTimer);

               this.typingTimer = setTimeout(()=> {
                    this.activePeer = false;

                }, 3000);
            });
            
        },
        methods: {
            getnames(){
                window.axios.get(`/api/deelnemers/${this.project.id}`)
                .then(response => {
                    return response.data})    
            
            },

            checkdate(task){
                const date = new Date(task.created_at);
                const d = date.getDay()+"/"+date.getMonth()+"/"+date.getFullYear();
                if (this.Last_date === d) {
                    return false;
                }
                else{
                    this.Last_date = d;
                    return true;
                }
           
            },
            gettime(task){
                const t = new Date(task.created_at);
                return t.getHours() + ':' + ('0' + t.getMinutes()).slice(-2);
            },
            tagPeers(){
                window.Echo.join('tasks.'+ this.project.id)

                    .whisper('typing', {
                        name: window.App.user.name,
                        
                    })
            },
            save(){
                 axios.post(`/api/projects/${this.project.id}/tasks`, {body: this.newTask, user_id: this.name1})
            .then(response => response.data)
            .then(this.newTask = '')
            .then(this.addTask);
            },

            addTask(task) {
                this.activePeer = false;

                this.project.tasks.push(task);
                // this.newTask = '';
               
        }
        }
    }
</script>
