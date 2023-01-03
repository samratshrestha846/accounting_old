<template>
	<div class="card post-card p-3">
		<div class="post-card__header mb-3"></div>
		<div class="post-card__footer">
			<p class="post-card__title mb-0"></p>
		</div>
	</div>
</template>

<style lang="scss" scoped>
	.post-card{

		&__header{
			min-width: 100%;
	        height: 68px;
	        background-color: #ddd;
	        animation: pulse-bg 1.2s ease-in-out infinite;
		}

		&__body{
			border-bottom: 1px solid #ccc;
		}

		.user_image{
	        margin-right: 10px;

	        img{
	            width: 32px;
	            height: 32px;
	            border-radius: 100%;
	            border: 1px solid #ccc;
	            overflow: hidden;
	            max-width: 36px;
	            background-color: #ddd;
	            animation: pulse-bg 1.2s ease-in-out infinite;
	        }
	    }

	    .user_name{
	    	min-width: 100px;
	        height: 16px;
	        background-color: #ddd;
	        animation: pulse-bg 1.2s ease-in-out infinite;
	    }
	    .tag{
	    	min-width: 100px;
	        height: 16px;
	        background-color: #ddd;
	        animation: pulse-bg 1.2s ease-in-out infinite;
	    }

	    &__date{
	    	min-width: 100px;
	        height: 16px;
	        background-color: #ddd;
	        animation: pulse-bg 1.2s ease-in-out infinite;
	    }

	    &__title{
	        min-width: 100%;
	        height: 24px;
	        background-color: #ddd;
	        animation: pulse-bg 1.2s ease-in-out infinite;
	    }
	}

	@keyframes pulse-bg {
		0% { background-color: #ddd; }
	    50% { background-color: #d0d0d0; }
	    100% { background-color: #ddd; }
	}
</style>
